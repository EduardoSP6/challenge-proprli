<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.UTF-8', 'pt_BR.UTF-8', 'portuguese');

        JsonResource::withoutWrapping();

        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info('Query', [
                    'time' => $query->time,
                    'sql' => $query->sql,
                    'bindings' => $query->bindings
                ]);
            });
        }
    }
}
