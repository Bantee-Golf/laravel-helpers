<?php
namespace EMedia\Helpers\Support;

use EMedia\Helpers\TimeHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait PublishesPackageMigrations
{

	public function publishPackageMigrations($migrationsDirectoryPath, array $migrationClassMap)
	{
		// the migration files should be the snake_case versions of the classes
		// eg. CreateOrdersTables => create_orders_tables.php

		if (count($migrationClassMap) == 0) return;

		foreach ($migrationClassMap as $migrationClass) {
			// if the migration exists, don't do anything
			if (class_exists($migrationClass)) continue;

			$timestamp = TimeHelper::getMicroTimestamp();
			$publishingOriginalFile = snake_case($migrationClass). '.php';
			$stubPath = $migrationsDirectoryPath . $publishingOriginalFile;

			if (!file_exists($stubPath))
				throw new FileNotFoundException("Migration file $stubPath not found");

			$target = $this->app->databasePath()
				.'/migrations/'.$timestamp.'_' . $publishingOriginalFile;

			$this->publishes([$stubPath => $target], 'migrations');
		}
	}

}