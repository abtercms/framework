<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Assets;

use AbterPhp\Framework\Assets\Factory\Minifier as MinifierFactory;
use MatthiasMullie\Minify\CSS as CssMinifier;
use MatthiasMullie\Minify\JS as JsMinifier;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class AssetManager
{
    const FILE_EXTENSION_CSS = '.css';
    const FILE_EXTENSION_JS  = '.js';

    const ERROR_EMPTY_GROUP_NAME = 'Group name must not be empty.';

    /** @var MinifierFactory */
    protected $minifierFactory;

    /** @var FileFinder */
    protected $fileFinder;

    /** @var CacheManager */
    protected $cacheManager;

    /** @var JsMinifier[] */
    protected $jsMinifiers = [];

    /** @var CssMinifier[] */
    protected $cssMinifiers = [];

    /**
     * AssetManager constructor.
     *
     * @param MinifierFactory $minifierFactory
     * @param FileFinder      $fileFinder
     * @param CacheManager    $cacheManager
     */
    public function __construct(MinifierFactory $minifierFactory, FileFinder $fileFinder, CacheManager $cacheManager)
    {
        $this->minifierFactory = $minifierFactory;
        $this->fileFinder      = $fileFinder;
        $this->cacheManager    = $cacheManager;
    }

    /**
     * @param string $groupName
     * @param string $rawPath
     *
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function addCss(string $groupName, string $rawPath)
    {
        $content = $this->fileFinder->read($rawPath . static::FILE_EXTENSION_CSS, $groupName);

        $this->getCssMinifier($groupName)->add($content);
    }

    /**
     * @param string $groupName
     * @param string $rawPath
     *
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function addJs(string $groupName, string $rawPath)
    {
        $content = $this->fileFinder->read($rawPath . static::FILE_EXTENSION_JS, $groupName);

        $this->getJsMinifier($groupName)->add($content);
    }

    /**
     * @param string $groupName
     * @param string $content
     */
    public function addCssContent(string $groupName, string $content)
    {
        $this->getCssMinifier($groupName)->add($content);
    }

    /**
     * @param string $groupName
     * @param string $content
     */
    public function addJsContent(string $groupName, string $content)
    {
        $this->getJsMinifier($groupName)->add($content);
    }

    /**
     * @param string $groupName
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     */
    public function renderCss(string $groupName): string
    {
        $content   = $this->getCssMinifier($groupName)->minify();
        $cachePath = $groupName . static::FILE_EXTENSION_CSS;

        $this->cacheManager->write($cachePath, $content);

        return $content;
    }

    /**
     * @param string $groupName
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     */
    public function renderJs(string $groupName): string
    {
        $content   = $this->getJsMinifier($groupName)->minify();
        $cachePath = $groupName . static::FILE_EXTENSION_JS;

        $this->cacheManager->write($cachePath, $content);

        return $content;
    }

    /**
     * @param string $cachePath
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     */
    public function renderImg(string $cachePath): string
    {
        $content = $this->fileFinder->read($cachePath);

        $this->cacheManager->write($cachePath, $content);

        return $content;
    }

    /**
     * @param string $groupName
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function ensureCssWebPath(string $groupName): string
    {
        $cachePath = $groupName . static::FILE_EXTENSION_CSS;

        if (!$this->cacheManager->has($cachePath)) {
            $this->renderCss($groupName);
        }

        return $this->cacheManager->getWebPath($cachePath);
    }

    /**
     * @param string $groupName
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function ensureJsWebPath(string $groupName): string
    {
        $cachePath = $groupName . static::FILE_EXTENSION_JS;

        if (!$this->cacheManager->has($cachePath)) {
            $this->renderJs($groupName);
        }

        return $this->cacheManager->getWebPath($cachePath);
    }

    /**
     * @param string $groupName
     *
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function ensureImgWebPath(string $cachePath): string
    {
        if (!$this->cacheManager->has($cachePath)) {
            $this->renderImg($cachePath);
        }

        return $this->cacheManager->getWebPath($cachePath);
    }

    /**
     * @param string $key
     *
     * @return CssMinifier
     */
    protected function getCssMinifier(string $key): CssMinifier
    {
        if (!array_key_exists($key, $this->cssMinifiers)) {
            $this->cssMinifiers[$key] = $this->minifierFactory->createCssMinifier();
        }

        return $this->cssMinifiers[$key];
    }

    /**
     * @param string $key
     *
     * @return JsMinifier
     */
    protected function getJsMinifier(string $key): JsMinifier
    {
        if (!array_key_exists($key, $this->jsMinifiers)) {
            $this->jsMinifiers[$key] = $this->minifierFactory->createJsMinifier();
        }

        return $this->jsMinifiers[$key];
    }
}
