<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('message', 'Alerta procesada.');
    }

    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('message', 'Historial forense actualizado.');
    }

    
    public function clearAll()
    {
        Auth::user()->notifications()->delete();

        return redirect()->back()->with('message', 'Bitácora de alertas purgada.');
    }
}
