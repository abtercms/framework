<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Assets\CacheManager;

class Dummy extends Flysystem
{
    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool
    {
        return false;
    }
}
