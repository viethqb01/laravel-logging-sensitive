<?php
/**
 * @Author apple
 * @Date Nov 15, 2024
 */

namespace Viethqb\LaravelLoggingSensitive\Providers;

use Illuminate\Support\ServiceProvider;
use Viethqb\LaravelLoggingSensitive\BaseLogger;
use Viethqb\LaravelLoggingSensitive\Contracts\LoggerInterface;

class LoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoggerInterface::class, BaseLogger::class);
    }

    public function boot(): void
    {
        $this->publishBaseClasses();
        BaseLogger::boot();
    }

    public function publishBaseClasses(): void
    {
        // Publish Logger
        $servicePath = __DIR__ . '/../Publish/Logger.php';
        $this->publishes([$servicePath => app_path('Log/Logger.php')], 'log');
    }
}