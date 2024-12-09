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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event): void {
            $user = auth()->user(); // Obtiene el usuario autenticado
            $rol = $user->rol_id;   // Accede al rol_id asociado al usuario

            // Definir menús según el rol
            $menuHeaders = [];

            if ($rol === 1) {
                // Menú para rol_id = 1 (Clientes)
                $menuHeaders = [
                    [
                        'text' => 'CLIENTES',
                        'icon' => 'fas fa-users',
                        'icon_color' => 'green',
                        'submenu' => [
                            [
                                'text' => 'Lista de Clientes',
                                'url' => 'admin/pages/empresas',
                                'icon' => 'fas fa-list',
                                'icon_color' => 'primary',
                            ],
                            [
                                'text' => 'Añadir Cliente',
                                'url' => 'admin/pages/clientes/add',
                                'icon' => 'fas fa-plus-circle',
                                'icon_color' => 'success',
                            ],
                        ],
                    ],
                ];
            } elseif ($rol === 2) {
                // Menú para rol_id = 2 (Agricultura y Ganadería)
                $menuHeaders = [
                    [
                        'text' => 'AGRICULTURA',
                        'icon' => 'fas fa-tractor',
                        'icon_color' => 'green',
                        'submenu' => [
                            [
                                'text' => 'Cartografía',
                                'url' => 'admin/pages/cartografia',
                                'icon' => 'fas fa-map-marked-alt',
                                'icon_color' => 'primary',
                            ],
                            [
                                'text' => 'Históricos',
                                'url' => 'admin/pages/historicos',
                                'icon' => 'fas fa-history',
                                'icon_color' => 'info',
                            ],
                            [
                                'text' => 'Análisis Predio',
                                'url' => 'admin/pages/analisis-predio',
                                'icon' => 'fas fa-chart-bar', // Icono para análisis de predio
                                'icon_color' => 'warning', // Amarillo
                            ],
                            [
                                'text' => 'Análisis Cultivo',
                                'url' => 'admin/pages/analisis-cultivo',
                                'icon' => 'fas fa-seedling', // Icono para análisis de cultivo
                                'icon_color' => 'success', // Verde
                            ],
                            [
                                'text' => 'Monitoreo',
                                'url' => 'admin/pages/monitoreo',
                                'icon' => 'fas fa-desktop', // Icono para monitoreo
                                'icon_color' => 'danger', // Rojo
                            ],
                        ],

                    ],
                    [
                        'text' => 'GANADERIA',
                        'icon' => 'fas fa-horse-head',
                        'icon_color' => 'yellow',
                        'submenu' => [
                            [
                                'text' => 'Potreros',
                                'url' => 'admin/pages/potreros',
                                'icon' => 'fas fa-tree',
                                'icon_color' => 'success',
                            ],
                            [
                                'text' => 'Hato Ganadero',
                                'url' => 'admin/pages/hato-ganadero',
                                'icon' => 'fas fa-paw',
                                'icon_color' => 'warning',
                            ],
                        ],
                    ],
                ];
            } else {
                // Menú por defecto para otros roles
                $menuHeaders = [
                    [
                        'text' => 'INICIO',
                        'icon' => 'fas fa-home',
                        'url' => 'admin/pages/dashboard',
                        'icon_color' => 'info',
                    ],
                ];
            }
            
            // Agregar el menú construido dinámicamente al evento
            foreach ($menuHeaders as $header) {
                $event->menu->add($header);
            }
        });

    }


}