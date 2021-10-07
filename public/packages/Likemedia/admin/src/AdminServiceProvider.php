<?php
namespace Admin;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 *  Admin Service Provider
 */
class AdminServiceProvider extends ServiceProvider {
	public function boot() {
		Schema::defaultStringLength(191);

		$this->loadRoutesFrom(__DIR__ . '/routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/./../resources/views', 'admin');
	}

	public function register() {
		$this->registerPublishables();
	}

	public function registerPublishables() {
		$basePath = dirname(__DIR__);

		$arrPublishable = ['migrations' => ["$basePath/publishable/database/migrations" => database_path('migrations') , ], 'config' => ["$basePath/publishable/config/admin.php" => config_path('admin.php') , ], ];

		foreach ($arrPublishable as $group => $paths) {
			$this->publishes($paths, $group);
		}
	}
}
