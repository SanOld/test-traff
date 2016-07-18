<?php

namespace App\Providers;
use app\Helpers;
use App\Helpers\Data;
use app\Helpers\DataController;
use Illuminate\Support\ServiceProvider;

use Illuminate\Contracts\Routing\ResponseFactory;

class DataServiceProvider extends ServiceProvider
{
  
  protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
     public function register(){
//          $this->app->bind('App\Helpers\Data', function(){
//               return new DataController();
//          });
        
     }
}
