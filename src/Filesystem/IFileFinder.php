<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Filesystem;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

interface IFileFinder
{
    public const DEFAULT_KEY = 'root';

    /**
     * @param FilesystemOperator $filesystem
     * @param string             $key
     * @param int                $priority
     */
    public function registerFilesystem(
        FilesystemOperator $filesystem,
        string $key = self::DEFAULT_KEY,
        int $priority = -1
    );

    /**
     * @param string $path
     * @param string $key
     *
     * @return bool
     */
    public function fileExists(string $path, string $key = IFileFinder::DEFAULT_KEY): bool;

    /**
     * @param string $path
     * @param string $key
     *
     * @return string|null
     * @throws FilesystemException
     */
    public function read(string $path, string $key = IFileFinder::DEFAULT_KEY): ?string;
}
