<?php
/**
 * Created by PhpStorm.
 * User: jjsquady
 * Date: 8/10/18
 * Time: 2:53 PM
 */

namespace ChatApi;


use Illuminate\Support\ServiceProvider;

class ChatApiServiceProvider extends ServiceProvider
{
    public function boot() {

        $source = realpath(__DIR__.'/../config/chat_api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([$source => config_path('chat_api.php')]);
        }

        $this->mergeConfigFrom($source, 'chat_api');
    }

    public function register()
    {

    }

}