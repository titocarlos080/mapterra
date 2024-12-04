<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; // Use the Event facade
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

use function PHPSTORM_META\map;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        /*Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = Auth::user();
            // Fetch the user's role efficiently
            $roleName = $user->rol->nombre; // Assuming you have a 'role' relationship on your User model
            // Get menu items associated with the user's role
            $menuItems = DB::select('
                SELECT menus.*
                FROM roles
                JOIN role_menus ON roles.id = role_menus.role_id
                JOIN menus ON menus.id = role_menus.menu_id
                WHERE roles.nombre = ?
            ', [$roleName]);

            $menuHeaders = DB::select('SELECT * FROM menus WHERE parent_id = 0');

            foreach ($menuHeaders as $header) {
                $headerItems = [];
                foreach ($menuItems as $item) {
                    if ($item->parent_id == $header->id) {
                        $headerItems[] = [
                            'text' => $item->name,
                            'url'  => $item->url,
                            'icon' => $item->icon,
                        ];
                    }
                }
                // Add header with its submenus
                $event->menu->add([
                    'text'    => $header->name,
                    'icon'    => $header->icon,
                    'submenu' => $headerItems,
                ]);
            }
        });*/
    }
}
