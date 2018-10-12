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

#### Distribute Bitbucket Deploy SSH Keys

For a server to access private repositories you need to assign the SSH PUBLIC Keys. Run the command below and follow the prompts. You'll need Bitbucket username, password and the public key.

```
php artisan setup:production:connect-deploy-keys
```

### Conversions

```
// Convert a UTC timestring to existing server's timezone
TimeConverters::toServerTimezone($UTCTimeString, $onlyDate = false)
```

### Resources

```
// Guess the primary resource path from a given URL.
entity_resource_path($url = '')
```

### Email

Email to webmaster with a simple text message.

Set the variables in `.env` file
```
WEBMASTER_EMAIL="webmaster@example.com"
OR
WEBMASTER_EMAIL="webmaster@example.com|sysadmin@example.com"

(Optional)
WEBMATER_REPLY_TO="reply@example.com
```

```
Webmaster::sendEmail($message, $subject = 'SYSTEM MESSAGE')
```

### Database

Create unique database tokens for a given column. Use this trait in an Eloquent model.

```
use \EMedia\Helpers\Database\CreatesUniqueTokens;
```
Then call the function

```
self::newUniqueToken('confirmation_token')
```

### Components

#### Menu

Build a Menu from various packages, by injecting menu items.

```
$m = (new \EMedia\Helpers\Components\Menu\MenuItem())->setText('Users')
						  ->setResource('manage.users.index')
						  ->setOrder(2)
						  ->setClass('fas fa-users');

// instead of setResource(), you and send a url with setUrl()

\EMedia\Helpers\Components\Menu\MenuBar::add($m);
OR
\EMedia\Helpers\Components\Menu\MenuBar::add($m, 'second-menu');
```

Getting the items back
```
\EMedia\Helpers\Components\Menu\MenuBar::menuItems();
OR
\EMedia\Helpers\Components\Menu\MenuBar::menuItems('second-menu');
```
