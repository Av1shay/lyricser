<?php

namespace App\Providers;

use App\Repositories\Contracts\SongRepositoryInterface;
use App\Repositories\SongRepository;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UploaderInterface;
use App\Services\LocalUploader;
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

        // Provide songs repository
        $this->app->singleton(SongRepositoryInterface::class, SongRepository::class);

        // Provide uploader
        $this->app->singleton(UploaderInterface::class, LocalUploader::class);

        // Provide services
        $this->app->singleton(SongServiceInterface::class, function($app) {
            return new SongService($app->make(SongRepositoryInterface::class), $app->make(UploaderInterface::class));
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
