<?php

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\ArrayHelper;

trait TagTrait
{
    /** @var array */
    protected $attributes = [];

    /** @var string */
    protected $tag = '';

    /**
     * @param string|null $tag
     *
     * @return $this
     */
    public function setTag(?string $tag = null): INode
    {
        $this->tag = $tag ?: static::DEFAULT_TAG;

        return $this;
    }

    /**
     * Retrieves all set attributes
     *
     * @return array of strings and nulls
     */
    public function getAttributes(): array
    {
        $result = [];
        foreach (array_keys($this->attributes) as $key) {
            $result[$key] = $this->getAttribute($key);
        }

        return $result;
    }

    /**
     * Checks if an attribute is set
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        if (!array_key_exists($key, $this->attributes)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves a single attribute
     *
     * @param string $key
     *
     * @return string|null
     */
    public function getAttribute(string $key): ?string
    {
        if (!array_key_exists($key, $this->attributes)) {
            throw new \RuntimeException('attribute does not exist');
        }

        if (null === $this->attributes[$key]) {
            return null;
        }

        return implode(' ', $this->attributes[$key]);
    }

    /**
     * Unsets a single attribute
     *
     * @param string $key
     *
     * @return $this
     */
    public function unsetAttribute(string $key): INode
    {
        if (!array_key_exists($key, $this->attributes)) {
            return $this;
        }

        unset($this->attributes[$key]);

        return $this;
    }

    /**
     * Removes a single attribute value
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function unsetAttributeValue(string $key, string $value): INode
    {
        if (!array_key_exists($key, $this->attributes)) {
            return $this;
        }

        if (!array_key_exists($value, $this->attributes[$key])) {
            return $this;
        }

        unset($this->attributes[$key][$value]);

        return $this;
    }

    /**
     * Unsets all existing attributes and replaces them with the newly provided attributes
     * Use addAttributes if you want to keep all existing attributes but the ones provided
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes): INode
    {
        $this->attributes = [];
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = ArrayHelper::formatAttribute($value);
        }

        return $this;
    }

    /**
     * Replaces all set provided attributes with new ones
     * Existing ones will be kept if not provided
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function addAttributes(array $attributes): INode
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = ArrayHelper::formatAttribute($value);
        }

        return $this;
    }

    /**
     * @param string      $key
     * @param string|null ...$values
     *
     * @return $this
     */
    public function setAttribute(string $key, ?string ...$values): INode
    {
        $this->attributes[$key] = ArrayHelper::formatAttribute($values);

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function appendToAttributes(array $attributes): INode
    {
        foreach ($attributes as $key => $values) {
            if (!isset($this->attributes[$key])) {
                $this->attributes[$key] = [];
            }

            $newValues = ArrayHelper::formatAttribute($values);
            if ($newValues === null) {
                $this->attributes[$key] = null;
                continue;
            }

            $this->attributes[$key] = array_merge($this->attributes[$key], $newValues);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string ...$values
     *
     * @return $this
     */
    public function appendToAttribute(string $key, string ...$values): INode
    {
        return $this->appendToAttributes([$key => $values]);
    }

    /**
     * @param string ...$values
     *
     * @return $this
     */
    public function appendToClass(string ...$values): INode
    {
        return $this->appendToAttribute(Html5::ATTR_CLASS, ...$values);
    }
}