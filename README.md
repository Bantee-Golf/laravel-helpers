## Laravel Helper functions

Supports Laravel 5.4

### Installation Instructions

Add the repository to `composer.json`
```
"repositories": [
	{
	    "type":"vcs",
	    "url":"git@bitbucket.org:elegantmedia/laravel-helpers.git"
	}
]
```

```
composer require emedia/helpers
```


## Database

If you want to clear the database, add this to your local service provider's `register` method.

```
if (!app()->environment('production')) {
	$this->commands(RefreshDatabaseCommand::class);
}
```