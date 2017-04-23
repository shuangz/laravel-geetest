<?php

namespace Shuangz\Geetest;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class GeetestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config.php' => config_path('geetest.php')]);

        //echo $this->app['config']['app']['name'];
        Route::get($this->app['config']['geetest']['path'], $this->app['config']['geetest']['invoke']);
        Validator::extend('geetest', [$this, 'extendValidate']);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('geetest', function ($app) {
            return new GeetestLib($app['config']['geetest']['id'], $app['config']['geetest']['key']);
        });
    }

    public function extendValidate($attribute, $value, $parameters, $validator)
    {
        $geetest    = $this->app->make('geetest');
        $request    = $this->app->make('request');
        $attributes = $validator->attributes();
        $username   = $parameters[0];
        $user_id    = $request->has($username) ? $request->input($username) : mt_rand(10000, 99999);
        $data = [
            'user_id'     => sha1($user_id),
            'client_type' => config('geetest.client_type'),
            'ip_address'  => $request->ip()
        ];

        $result = $geetest->success_validate(
                     $attributes['geetest_challenge'],
                     $attributes['geetest_validate'],
                     $attributes['geetest_seccode'],
                     $data
                 );

        return (boolean) $result;

    }
}
