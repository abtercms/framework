<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Assets;

use League\Flysystem\FilesystemInterface;

interface ICacheManager
{
    const DEFAULT_KEY = 'root';

    /**
     * @param FilesystemInterface $filesystem
     * @param callable|null       $checker
     * @param int                 $priority
     */
    public function registerFilesystem(FilesystemInterface $filesystem, callable $checker = null, int $priority = -1);

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param string $path
     *
     * @return string|null
     */
    public function read(string $path): ?string;

    /**
     * @param string $path
     * @param string $content
     *
     * @return int
     */
    public function write(string $path, string $content): bool;

    /**
     * @param string $path
     *
     * @return string
     */
    public function getWebPath(string $path): string;

    public function flush();
}
