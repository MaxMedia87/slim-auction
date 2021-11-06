<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\Id;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    public function testCreatedAndReturnTheCorrectData(): void
    {
        $id = new Id('8b979a49-045e-4af0-8fd5-d50cbf7cf705');

        self::assertEquals('8b979a49-045e-4af0-8fd5-d50cbf7cf705', $id->value());
    }

    /**
     * @param string $value
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @dataProvider invalidCreationDataProvider
     *
     * @psalm-param  class-string<\Throwable> $exceptionClass
     */
    public function testThrowsTheExceptionsWhenCreatingFromInvalidData(
        string $value,
        string $exceptionClass,
        string $exceptionMessage
    ): void {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        new Id($value);
    }

    /**
     * @return \Iterator<array>
     */
    public function invalidCreationDataProvider(): \Iterator
    {
        yield [
            '',
            \InvalidArgumentException::class,
            'Value "" is not a valid UUID.'
        ];
        yield [
            '12345',
            \InvalidArgumentException::class,
            'Value "12345" is not a valid UUID.'
        ];
    }

    public function testGenerate(): void
    {
        $generateId = Id::generate();

        $id = new Id($generateId->value());

        self::assertNotEmpty($generateId);
        self::assertEquals($generateId->value(), $id->value());
    }
}
