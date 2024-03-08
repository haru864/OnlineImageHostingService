<?php

namespace Settings;

class Settings
{
    private const ENV_PATH =  '.env';
    private const PUBLIC_ENV_PATH =  '.public.env';

    public static function env(string $pair): string
    {
        $privateConfig = parse_ini_file(__DIR__ . '/../../' . self::ENV_PATH);
        $publicConfig = parse_ini_file(__DIR__ . '/../../' . self::PUBLIC_ENV_PATH);
        if ($privateConfig === false) {
            throw new \Exception("ERROR: .env not found");
        }
        if ($publicConfig === false) {
            throw new \Exception("ERROR: .public.env not found");
        }
        $config = $privateConfig + $publicConfig;
        return $config[$pair];
    }
}
