<?php

namespace Viethqb\LaravelLoggingSensitive;

use Closure;
use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;

class BaseLogger
{
    public static array $sensitiveKeys = [
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
    private static ?\Closure $callbackInvoke = null;

    public static function boot(): string
    {
        self::$logId = Str::random();
        return self::$logId;
    }

    /**
     * @Author viethqb
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
            call_user_func(self::$callbackInvoke, $record);
            return $record;
        });
    }

    /**
     * @Author viethqb
     * @Date Nov 15, 2024
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
     * @Author viethqb
     * @Date Nov 15, 2024
     *
     * @param $key
     * @return bool
     */
    public function checkKeyIsSensitive($key): bool
    {
        foreach (self::$sensitiveKeys as $sensitiveWord) {
            if (str_contains(strtolower($key), $sensitiveWord)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Author viethqb
     * @Date Nov 15, 2024
     *
     * @param array $keys
     */
    public static function setSensitiveKeys(array $keys): void
    {
        self::$sensitiveKeys = $keys;
    }

    /**
     * @Author viethqb
     * @Date Nov 15, 2024
     *
     * @param Closure $callback
     */
    public static function setCallbackInvoke(Closure $callback): void
    {
        self::$callbackInvoke = $callback;
    }
}

