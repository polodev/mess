<?php

namespace App\Providers;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // \Storage::extend('google', function ($app, $config) {
      //     $client = new \Google_Client();
      //     $client->setClientId($config['clientId']);
      //     $client->setClientSecret($config['clientSecret']);
      //     $client->refreshToken($config['refreshToken']);
      //     $service = new \Google_Service_Drive($client);
      //     $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folderId']);
      //     return new \League\Flysystem\Filesystem($adapter);
      // });
    }
}
