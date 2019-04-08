<?php

namespace AbterPhp\Framework\I18n;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MockTranslatorFactory
{
    /**
     * @param TestCase   $testCase
     * @param array|null $translations
     *
     * @return ITranslator|MockObject|null
     */
    public static function createSimpleTranslator(TestCase $testCase, ?array $translations): ?ITranslator
    {
        if (null === $translations) {
            return null;
        }

        /** @var ITranslator|MockObject $mockTranslator */
        $translatorMock = (new MockBuilder($testCase, ITranslator::class))
            ->disableOriginalConstructor()
            ->setMethods(['translate', 'canTranslate'])
            ->getMock();

        $translatorMock
            ->expects($testCase->any())
            ->method('translate')
            ->willReturnCallback(
                function ($key) use ($translations) {
                    if (array_key_exists($key, $translations)) {
                        return $translations[$key];
                    }

                    return "{{translation missing: $key}}";
                }
            );

        $translatorMock
            ->expects($testCase->any())
            ->method('canTranslate')
            ->willReturnCallback(
                function ($key) use ($translations) {
                    if ($translations && array_key_exists($key, $translations)) {
                        return true;
                    }

                    return false;
                }
            );

        return $translatorMock;
    }
}
