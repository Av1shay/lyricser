<?php

namespace App\Providers;

use App\Repositories\Contracts\SongRepositoryInterface;
use App\Repositories\Contracts\WordRepositoryInterface;
use App\Repositories\SongRepository;
use App\Repositories\WordRepository;
use App\Services\Contracts\SongServiceInterface;
use App\Services\Contracts\UploaderInterface;
use App\Services\Contracts\WordServiceInterface;
use App\Services\LocalUploader;
use App\Services\SongService;
use App\Services\WordService;
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

        // Provide words repository
        $this->app->singleton(WordRepositoryInterface::class, WordRepository::class);

        // Provide uploader
        $this->app->singleton(UploaderInterface::class, LocalUploader::class);

        // Provide services
        $this->app->singleton(SongServiceInterface::class, function($app) {
            return new SongService($app->make(SongRepositoryInterface::class), $app->make(UploaderInterface::class));
        });

        $this->app->singleton(WordServiceInterface::class, function($app) {
            return new WordService($app->make(WordRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
