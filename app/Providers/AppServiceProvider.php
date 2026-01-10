<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('web_settings')) {
                $appVersion = \App\Models\WebSetting::where('key', 'app_version')->value('value') ?? '1.0.4';
                \Illuminate\Support\Facades\View::share('appVersion', $appVersion);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('appVersion', '1.0.4');
        }
    }
}
