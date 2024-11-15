<?php

namespace Viethqb\LaravelLoggingSensitive;

use Illuminate\Support\Str;
use Viethqb\LaravelLoggingSensitive\Contracts\LoggerInterface;

class BaseLogger implements LoggerInterface
{
    public const SENSITIVE_WORDS = [
        "api_key",
        "api key",
        'apikey',
        'secret_key',
        'secret key',
        'secretKey',
        'user name',
        'userName',
        "password",
    ];

    public static string $logId;

    /**
     * Boot method to initialize log ID (UUID).
     *
     * @return string
     */
    public static function boot(): string
    {
        self::$logId = (string) Str::uuid();
        return self::$logId;
    }

    /**
     * Get the current log ID (UUID).
     *
     * @return string
     */
    public static function getLogId(): string
    {
        return self::$logId;
    }

    /**
     * Mask sensitive data in logs and add UUID to context.
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record): array
    {
        $record['context'] = $this->hideSensitiveData($record['context']);
        $record['context']['request_uuid'] = self::getLogId();

        $this->postInvoke($record);
        return $record;
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
        foreach (self::SENSITIVE_WORDS as $sensitiveWord) {
            if (str_contains(strtolower($key), $sensitiveWord)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Author apple
     * @Date Nov 15, 2024
     * Note: Custom xử lý sau khi invoke
     *        + get push error to telegram, sentry ...vv
     *        + level_name = $record['level_name']
     *        + value của level_name (DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY)
     * @param array $record
     */
    public function postInvoke(array $record)
    {

    }
}
