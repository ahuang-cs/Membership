<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LegacyAppServiceProvider extends ServiceProvider
{
  protected $defer = true;

  public function register()
  {
    $this->app->singleton("LegacyApp", function ($app) {

      // here are the contents of the legacy index.php:
      require_once base_path() . "/legacy_app/index.php";
      return $nezamyApp;
    });
  }
}
