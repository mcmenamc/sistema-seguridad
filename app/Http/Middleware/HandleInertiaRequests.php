<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                    'notifications' => $request->user()->notifications()->limit(10)->get(),
                    'unread_notifications_count' => $request->user()->unreadNotifications()->count(),
                ] : null,
            ],
            'sidebar' => $request->user() ? $this->getSidebarMenu($request->user()) : [],
        ];
    }

    protected function getSidebarMenu($user): array
    {
        $modules = Module::where('is_active', true)
            ->orderBy('order')
            ->get();

        $menu = [];
        $groups = $modules->groupBy('group_name');

        foreach ($groups as $groupName => $items) {
            $filteredItems = $items->filter(function ($item) use ($user) {
                return $user->can($item->permission);
            })->map(function ($item) {
                return [
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'route' => $item->route,
                ];
            })->values()->all();

            if (count($filteredItems) > 0) {
                $menu[] = [
                    'title' => $groupName,
                    'items' => $filteredItems,
                ];
            }
        }

        return $menu;
    }
}
