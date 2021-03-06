<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Config;

use AbterPhp\Framework\Constant\Env;
use Opulence\Environments\Environment;

class Routes
{
    public const ASSETS_PATH = '/:path';

    /** @var string|null */
    protected static $mediaUrl;

    /** @var string|null */
    protected static $cacheUrl;

    /** @var string|null */
    protected static $assetsPath;

    /**
     * @param string $mediaUrl
     */
    public static function setMediaUrl(string $mediaUrl): void
    {
        static::$mediaUrl = $mediaUrl;
    }

    /**
     * @return string
     */
    public static function getMediaUrl(): string
    {
        if (null !== static::$mediaUrl) {
            return static::$mediaUrl;
        }

        static::$mediaUrl = (string)Environment::getVar(Env::MEDIA_BASE_URL);

        return static::$mediaUrl;
    }

    /**
     * @param string $cacheUrl
     */
    public static function setCacheUrl(string $cacheUrl): void
    {
        static::$cacheUrl = $cacheUrl;
    }

    /**
     * @return string
     */
    public static function getCacheUrl(): string
    {
        if (null !== static::$cacheUrl) {
            return static::$cacheUrl;
        }

        $cachePath = Environment::getVar(Env::CACHE_BASE_PATH, '');
        if (!$cachePath) {
            return '';
        }

        $cacheUrl = sprintf(
            '%s%s%s',
            rtrim(static::getMediaUrl(), DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR,
            ltrim($cachePath, DIRECTORY_SEPARATOR)
        );

        static::$cacheUrl = (string)$cacheUrl;

        return static::$cacheUrl;
    }

    /**
     * @return string
     */
    public static function getAssetsPath(): string
    {
        if (null !== static::$assetsPath) {
            return static::$assetsPath;
        }

        $basePath = Environment::getVar(Env::CACHE_BASE_PATH, '');
        if (!$basePath) {
            return '';
        }

        $assetsPath = sprintf('%s%s', $basePath, static::ASSETS_PATH);

        static::$assetsPath = (string)$assetsPath;

        return static::$assetsPath;
    }
}
