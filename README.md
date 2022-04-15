# Laravel: Workcast API integration

[![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-workcast?color=%234dc71f)](https://github.com/yaroslawww/laravel-workcast/blob/master/LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-workcast)](https://packagist.org/packages/yaroslawww/laravel-workcast)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-workcast)](https://packagist.org/packages/yaroslawww/laravel-workcast)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-workcast/?branch=master)

Api documentation you can find [there](https://insite.workcast.com/api-webinar-registration-documentation)
and [there](https://insite.workcast.com/api-webinar-reporting-documentation)

## Installation

You can install the package via composer:

```bash
composer require yaroslawww/laravel-workcast
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

```injectablephp
$pagination = Workcast::events()->list([ 'limit' => 50 ]);
    foreach ($pagination->items() as $item) {
        echo $item['eventPak'];
    }

    if ($pagination->hasNext()) {
        echo $pagination->nextLink();
        // Workcast::events()->callPagination($pagination->nextLink());
    }
```

```injectablephp
$item = Workcast::events()->get(22);
dd($item->json());
```

An example of a custom endpoint (in case this package does not contain the required endpoint)
```injectablephp
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
        return "events/{$this->eventId}/" . $this->key();
    }

    public function key(): string
    {
        return 'presenters';
    }
}

(new Presenters(Workcast::getAuth(), 33))->get(55)->json();
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
