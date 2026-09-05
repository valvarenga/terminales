<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ([\App\Models\Autobuses::class, \App\Models\Terminales::class,
            \App\Models\Municipios::class, \App\Models\Departamentos::class,
            \App\Models\SugerenciaTerminal::class, \App\Models\User::class] as $model) {
            $model::observe(\App\Observers\AuditObserver::class);
        }
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
