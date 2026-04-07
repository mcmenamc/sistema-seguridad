<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->role($request->role);
        }

        return Inertia::render('Users/Index', [
            'users' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:Administrador,Operador,Cliente',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Usuario registrado',
            'description' => "Se ha inyectado una nueva identidad al sistema: {$user->name} con el rol de {$request->role}",
        ]);

        return redirect()->back()->with('message', 'Usuario registrado con éxito.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:Administrador,Operador,Cliente',
        ]);

        $user->update($request->only('name', 'email'));

        if ($request->password) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Usuario actualizado',
            'description' => "Se han recalibrado los privilegios de la identidad: {$user->name} ({$request->role})",
        ]);

        return redirect()->back()->with('message', 'Ficha de usuario actualizada.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors(['message' => 'No puedes purgar tu propia identidad del sistema.']);
        }

        $name = $user->name;
        $user->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Usuario purgado',
            'description' => "Se ha eliminado permanentemente la identidad: {$name}",
        ]);

        return redirect()->back()->with('message', 'Usuario eliminado del sistema.');
    }
}
