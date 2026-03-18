<?php

namespace App\Providers;

use App\Services\PermisoService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermisoService::class);
    }

    public function boot(): void
    {
        Blade::directive('permiso', function ($expression) {
            return "<?php if(app(\App\Services\PermisoService::class)->puede({$expression})): ?>";
        });

        Blade::directive('endpermiso', function () {
            return "<?php endif; ?>";
        });
    }
}
