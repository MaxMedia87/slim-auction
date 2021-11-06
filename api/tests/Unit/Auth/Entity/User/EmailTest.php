<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testCreatedAndReturnTheCorrectData(): void
    {
        $value = 'maxim@mail.ru';

        $email = new Email($value);

        self::assertEquals($value, $email->value());
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

        new Email($value);
    }

    /**
     * @return \Iterator<array>
     */
    public function invalidCreationDataProvider(): \Iterator
    {
        yield [
            '',
            \InvalidArgumentException::class,
            'E-mail адрес обязателен для заполнения.'
        ];

        yield [
            'maxim@mail',
            \InvalidArgumentException::class,
            'Email адрес "maxim@mail" не валиден.'
        ];
    }
}
