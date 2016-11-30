<?php

namespace App\Common\LaravelOpenWechat;

use App\Common\OpenWechat\Core\App;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * 延迟加载.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $source => config_path('openwechat.php'),
                ]);
            }

        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('openwechat');
        }

        $this->mergeConfigFrom($source, 'openwechat');
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(['App\\Common\\OpenWechat\\Core\\App' => 'openwechat'], function ($app) {
            $app = new App(array( 'appid'=>'wx5a8725f25730a6df',
                'token'=>'pamtest',
                'encodingAesKey'=>'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
                'appsecret'=>'54e23aecf7621bdc34bc1264ead3fe17'));
            $app->cache = new CacheBridge();
            return $app;
        });
    }

    /**
     * 提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return ['openwechat', 'App\Common\OpenWechat\Core\App'];
    }

}
