# Laravel: Workcast API integration

![Packagist License](https://img.shields.io/packagist/l/think.studio/laravel-workcast?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/laravel-workcast)](https://packagist.org/packages/think.studio/laravel-workcast)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/laravel-workcast)](https://packagist.org/packages/think.studio/laravel-workcast)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-workcast/?branch=main)

Api documentation you can find [there](https://api-docs.workcast.com/)

## Installation

You can install the package via composer:

```bash
composer require think.studio/laravel-workcast
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="LaravelWorkcast\ServiceProvider" --tag="config"
```

Configuration in *.env*

```dotenv
WORKCAST_API_KEY='066t...L21135A='
```

## Usage

Paginated request example for listings:

```php
$pagination = Workcast::events()->list([ 'limit' => 50 ]);
foreach ($pagination->items() as $item) {
    echo $item['eventPak'];
}

if ($pagination->hasNext()) {
    echo $pagination->nextLink();
    // Workcast::events()->callPagination($pagination->nextLink());
}
```

Single entity request:

```php
$item = Workcast::events()->get(22);
dd($item->json());
```

By default in package specified this list of endpoints:

```php

```

But you can also specify you own endpoint:

```php
use LaravelWorkcast\Endpoints\AbstractEndpoint;
use LaravelWorkcast\Endpoints\HasRestFullRead;
use LaravelWorkcast\Endpoints\WithRestFullRead;
class Presenters extends AbstractEndpoint implements HasRestFullRead
{
    use WithRestFullRead;

    protected int $eventId;

    public function __construct(Auth $auth, int $eventId)
    {
        $this->eventId = $eventId;

        parent::__construct($auth);
    }

    public function baseUrl(): string
    {
        return "presenters/{$this->eventId}/sessions/" . $this->key();
    }

    public function key(): string
    {
        return 'presenters';
    }
}

$pagination = (new Presenters(Workcast::getAuth(), 33))->list([ 'limit' => 50 ]);
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
