<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Tumblr API
        $this->app->bind('Tumblr\API\Client', function () {
            return new \Tumblr\API\Client(
                getenv('TUMBLR_CONSUMER_KEY'),
                getenv('TUMBLR_SECRET_KEY')
            );
        });

        // Spotify API
        $this->app->bind('SpotifyWebAPI\Session', function () {
            return new \SpotifyWebAPI\Session(
                getenv('SPOTIFY_CLIENT_ID'),
                getenv('SPOTIFY_CLIENT_SECRET')
            );
        });

        // App Contracts
        $this->app->bind('App\Contracts\Repositories\SpotifyRepositoryContract', 'App\Repositories\SpotifyRepository');
        $this->app->bind('App\Contracts\Repositories\TumblrRepositoryContract', 'App\Repositories\TumblrRepository');
        $this->app->bind('App\Contracts\Repositories\UntappdRepositoryContract', 'App\Repositories\UntappdRepository');
    }
}
