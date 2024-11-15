<?php

namespace Viethqb\LaravelLoggingSensitive\Contract;

interface LoggerInterface
{
    public static function boot();

    public static function getLogId();

    public function checkKeyIsSensitive($key);

    public function hideSensitiveData(array $data);
}
