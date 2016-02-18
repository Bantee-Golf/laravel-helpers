## Laravel Helper functions


### Migration Helpers

Use `PublishesPackageMigrations` trait in a service provider to add migration file publishing support.

eg:
```
	use PublishesPackageMigrations;

	public function boot()
	{
		$this->publishMigrations();
	}

	public function publishMigrations()
	{
		// the migration files should be the snake_case versions of the classes
		// eg. CreateOrdersTables => create_orders_tables.php
		$migrationClassMap = [
			'CreateOrdersTables',
			'AddColumnsToOrdersTables',
		];

		$migrationsDirPath = __DIR__ . '/../migrations/';
        $this->publishPackageMigrations($migrationsDirPath, $migrationClassMap);
	}

```