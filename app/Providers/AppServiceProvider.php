<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);//设置默认长度 myisam索引键长度和最大只能是1000 Bytes 最大就只能是1000/4=250 [utf8mb4]  innoDB 为3072Bytes 3072/4=768
        //
    }
}
