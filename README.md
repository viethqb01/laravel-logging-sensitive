# Laravel Logger

This base code logger custom in laravel
+ Hide data sensitive 
+ Add request ID to serve the request flow

## Install

```shell
composer require viethqb/laravel-logging-sensitive
```

## Update config/logging.php
+ Update BaseLogger to config logging:

```shell
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => 'debug',
    'days' => 14,
    'tap' => [Viethqb\LaravelLoggingSensitive\BaseLogger::class],
]
```

## Update config provider from config/app.php
```shell
Viethqb\LaravelLoggingSensitive\Providers\LoggerServiceProvider::class
```

## Publish configuration file and Base Classes

```shell
php artisan vendor:publish --provider="Viethqb\LaravelLoggingSensitive\Providers\LoggerServiceProvider"
```

## Demo request result 
+ input

```shell
{
    "user_id": 1
    "user_name": "viethqb"
    "password": "1234567@"
}
 ```

+ output

```shell
{
    "request_uuid": "123e4567-e89b-12d3-a456-426614174000",
    "user_id": 1
    "user_name": "*****"
    "password": "******"
}
 ```