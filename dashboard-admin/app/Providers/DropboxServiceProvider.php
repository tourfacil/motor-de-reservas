<?php namespace App\Providers;

use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;

/**
 * Class DropboxServiceProvider
 * @package App\Providers
 */
class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient($config['authorization_token']);
            return new Filesystem(new DropboxAdapter($client));
        });
    }
}
