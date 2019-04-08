<?php

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\Html\Helper\ArrayHelper;

class StubAttributeFactory
{
    const ATTRIBUTE_FOO = 'foo';
    const ATTRIBUTE_BAR = 'bar';
    const ATTRIBUTE_BAZ = 'baz';

    const VALUE_FOO = 'foo';
    const VALUE_BAZ = 'baz';
    const VALUE_BAR_BAZ = 'bar baz';

    /**
     * @param array $extraAttributes
     *
     * @return array
     */
    public static function createAttributes(array $extraAttributes = []): array
    {
        $attributes = [
            static::ATTRIBUTE_FOO => [static::VALUE_FOO, static::VALUE_BAZ],
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $attributes = ArrayHelper::unsafeMergeAttributes($attributes, $extraAttributes);

        return $attributes;
    }
}
