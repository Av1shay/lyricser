<?php

namespace App\Providers;

use App\Repositories\Contracts\SongRepositoryInterface;
use App\Repositories\SongRepository;
use App\Services\Contracts\SongServiceInterface;
use App\Services\SongService;
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
        $this->app->singleton(SongRepositoryInterface::class, SongRepository::class);

        $this->app->singleton(SongServiceInterface::class, function($app) {
            return new SongService($app->make(SongRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
