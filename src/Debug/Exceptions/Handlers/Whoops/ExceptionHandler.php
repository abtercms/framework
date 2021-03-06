<?php

namespace AbterPhp\Framework\Debug\Exceptions\Handlers\Whoops;

use Opulence\Debug\Exceptions\Handlers\IExceptionHandler;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @SuppressWarnings(PHPMD)
 */
class ExceptionHandler implements IExceptionHandler
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var ExceptionRenderer */
    protected $whoopsRenderer;

    /** @var string|null */
    protected $sapi;

    /**
     * @param LoggerInterface   $logger
     * @param ExceptionRenderer $whoopsRenderer
     * @param array             $exceptionsSkipped
     */
    public function __construct(LoggerInterface $logger, ExceptionRenderer $whoopsRenderer, array $exceptionsSkipped)
    {
        $this->logger         = $logger;
        $this->whoopsRenderer = $whoopsRenderer;
    }

    /**
     * @return string|null
     */
    public function getSapi(): ?string
    {
        if (null === $this->sapi) {
            $this->sapi = PHP_SAPI;
        }

        return $this->sapi;
    }

    /**
     * @param string|null $sapi
     *
     * @return $this
     */
    public function setSapi(?string $sapi): ExceptionHandler
    {
        $this->sapi = $sapi;

        return $this;
    }

    /**
     * Handles an exception
     *
     * @param Throwable $ex The exception to handle
     */
    public function handle($ex)
    {
        $this->whoopsRenderer->render($ex);
    }

    /**
     * Registers the handler with PHP
     */
    public function register()
    {
        $whoops = $this->whoopsRenderer->getRun();

        $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler($this->logger));
        if ($this->getSapi() !== 'cli') {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        }

        $whoops->register();
    }
}
