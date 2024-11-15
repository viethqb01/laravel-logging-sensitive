<?php

namespace Viethqb\LaravelLoggingSensitive;

use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;

class BaseLogger
{
    public const SENSITIVE_KEYS = [
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
    ];

    private static string $logId;

    public static function boot(): string
    {
        self::$logId = Str::random();
        return self::$logId;
    }

    /**
     * @Author apple
     * @Date Nov 15, 2024
     *
     * @param $logger
     */
    public function __invoke($logger): void
    {
        $logId = self::$logId;

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] %channel%.%level_name%: [Log ID: $logId] %message% %context%\n",
                "Y-m-d H:i:s"
            ));
        }

        $logger->pushProcessor(function ($record) {
            $record['context'] = $this->hideSensitiveData($record['context']);
            return $record;
        });
    }

    /**
     * Recursively masks sensitive data in an array.
     *
     * @param array $data
     * @return array
     */
    public function hideSensitiveData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->hideSensitiveData($value);
            } elseif (is_string($key) && $this->checkKeyIsSensitive($key)) {
                $data[$key] = '******';
            }
        }
        return $data;
    }

    /**
     * Check if a key is sensitive.
     *
     * @param $key
     * @return bool
     */
    public function checkKeyIsSensitive($key): bool
    {
        foreach (self::SENSITIVE_KEYS as $sensitiveWord) {
            if (str_contains(strtolower($key), $sensitiveWord)) {
                return true;
            }
        }
        return false;
    }
}

