<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TesseractServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('tesseract', function ($app) {
            return new TesseractOCR();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
