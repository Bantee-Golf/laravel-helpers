## Laravel Helper functions

Supports Laravel 5.7

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

### Available Commands


#### Refresh Database

Not available in `production` environment.
```
// Remove all existing tables and re-seed the database
php artisan db:refresh

// Reset the database, but don't migrate
php artisan db:refresh --nomigrate

// Reset the database, but don't seed
php artisan db:refresh --noseed
```

#### Composer Autoload
```
php artisan composer:dump-autoload
```

#### Conversions

```
// Convert a UTC timestring to existing server's timezone
TimeConverters::toServerTimezone($UTCTimeString, $onlyDate = false)
```

#### Resources

```
// Guess the primary resource path from a given URL.
entity_resource_path($url = '')
```
