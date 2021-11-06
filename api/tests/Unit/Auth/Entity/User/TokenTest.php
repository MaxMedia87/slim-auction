<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testCreatedAndReturnTheCorrectData(): void
    {
        $date = new \DateTimeImmutable();
        $value = '8b979a49-045e-4af0-8fd5-d50cbf7cf705';
        $token = new Token($value, $date);

        self::assertInstanceOf(\DateTimeImmutable::class, $token->expires());
        self::assertEquals($value, $token->value());
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

        new Token($value, new \DateTimeImmutable());
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
            '123243',
            \InvalidArgumentException::class,
            'Value "123243" is not a valid UUID.'
        ];
    }
}
