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

## Config key hide sensitive
+ Default 
```shell
$sensitiveKeys = [
    "api_key",
    "api key",
    'apikey',
    'secret_key',
    'secret key',
    'secretKey',
    'user name',
    'user_name',
    'userName',
    "password",
] 
```
+ Config custom key hide in method boot AppServiceProvider
```shell
 BaseLogger::setSensitiveKeys([""]);
```

+ Config custom function listen log boot AppServiceProvider
```shell
BaseLogger::setCallbackInvoke(function ($record) {
    // Todo custom function callback post invoke log  
});
```

## Demo request result 
+ input

```shell
Log::info('Log', ['api_key' => 'SB8WHshk5WWAbIlPVZBvzCJTJCsahpq87q', 'password' => "123456"]);
 ```

+ output Log

```shell
[2024-11-15 08:56:09] local.INFO: [Log ID: JFFAo42mzFzjUDaV] Log {"api_key":"******", "password":"******"}
 ```