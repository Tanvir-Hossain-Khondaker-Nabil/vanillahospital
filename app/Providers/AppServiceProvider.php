<?php

namespace App\Providers;

use App\Http\Traits\ObserverTrait;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ObserverTrait;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(255);
        $this->getObservers();
        if (Schema::hasTable('settings')) {
            $settings = new Setting();

            if(Auth::check())
            {
                $settings = $settings->where('company_id', Auth::user()->company_id);
            }

            foreach ($settings->all() as $setting) {
                Config::set('settings.'.$setting->key, $setting->value);
            }
        }
        

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {}
}
