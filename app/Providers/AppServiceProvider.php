<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));
        Schema::defaultStringLength(191);

        try{
            config([
                'global' => settings()->all()

            ]);

            Config::set('mail.driver',\config('global.config_type'));
            Config::set('mail.host',\config('global.email_host'));
            Config::set('mail.port',\config('global.email_port'));
            Config::set('mail.username',\config('global.email_username'));
            Config::set('mail.password',\config('global.email_password'));


        }catch (\Exception $exception)
        {
//            $str = 'Settings Migration Needed';
//          return $str;
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
