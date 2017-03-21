<?php


namespace EMedia\Helpers;

use EMedia\Helpers\Console\Commands\Database\RefreshDatabaseCommand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		if (!app()->environment('production')) {
			$this->commands(RefreshDatabaseCommand::class);
		}
	}

}