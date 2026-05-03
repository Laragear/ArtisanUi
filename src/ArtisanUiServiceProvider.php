<?php

namespace Laragear\ArtisanUi;

use Illuminate\Support\ServiceProvider;
use Laragear\ArtisanUi\Console\ArtisanUiCommand;

class ArtisanUiServiceProvider extends ServiceProvider
{
    /**
     * Boot the package to register console commands and intercept bare php artisan calls.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([ArtisanUiCommand::class]);
        }
    }
}
