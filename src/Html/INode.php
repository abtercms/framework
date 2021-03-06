<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\I18n\ITranslator;

interface INode
{
    /**
     * @param string|INode|null $content
     *
     * @return INode
     */
    public function setContent($content): INode;

    /**
     * @see Node::$intents
     *
     * @param string $intent
     *
     * @return bool
     */
    public function hasIntent(string $intent): bool;

    /**
     * @see Node::$intents
     *
     * @return string[]
     */
    public function getIntents(): array;

    /**
     * @see Node::$intents
     *
     * @param string ...$intent
     *
     * @return INode
     */
    public function setIntent(string ...$intent): INode;

    /**
     * Adds a single intent
     *
     * @see Node::$intents
     *
     * @param string ...$intent
     *
     * @return INode
     */
    public function addIntent(string ...$intent): INode;

    /**
     * @param ITranslator|null $translator
     *
     * @return INode
     */
    public function setTranslator(?ITranslator $translator): INode;

    /**
     * @return ITranslator|null
     */
    public function getTranslator(): ?ITranslator;

    /**
     * Checks if the current component matches the arguments provided
     *
     * @param string|null $className
     * @param string      ...$intents
     *
     * @return bool
     */
    public function isMatch(?string $className = null, string ...$intents): bool;

    /**
     * @return string
     */
    public function __toString(): string;
}
