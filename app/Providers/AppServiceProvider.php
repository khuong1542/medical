<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Request;

class AppServiceProvider extends ServiceProvider
{
    public $arrModules;
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		require_once base_path('app/Core/GlobalFunction.php');
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		if (Request::is('admin/*')) {
			$arrModules = config('moduleAdmin');
			$this->arrModules = $arrModules;
			view()->composer('*', function ($view) {
				$view->with('sidebarItems', $this->arrModules);
			});
		}
	}
}
