<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Service;

use App\Auth\Service\PasswordHashGenerator;
use PHPUnit\Framework\TestCase;

class PasswordHashGeneratorTest extends TestCase
{
    public function testCreated(): void
    {
        $hashGenerator = new PasswordHashGenerator(16);

        $password = 'my-password';
        $hash = $hashGenerator->hash($password);

        self::assertNotEmpty($hash);
        self::assertNotEquals($password, $hash);
    }

    /**
     * @param string $password
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @dataProvider invalidCreationDataProvider
     *
     * @psalm-param  class-string<\Throwable> $exceptionClass
     */
    public function testThrowsTheExceptionsWhenCreatingFromInvalidData(
        string $password,
        string $exceptionClass,
        string $exceptionMessage
    ): void {
        $hashGenerator = new PasswordHashGenerator(16);

        self::expectException($exceptionClass);
        self::expectExceptionMessage($exceptionMessage);

        $hashGenerator->hash($password);
    }

    /**
     * @return \Iterator<array>
     */
    public function invalidCreationDataProvider(): \Iterator
    {
        yield [
            '',
            \InvalidArgumentException::class,
            'Пароль обязателен для заполнения.'
        ];
    }

    public function testValidate(): void
    {
        $hashGenerator = new PasswordHashGenerator(16);

        $password = 'my-password';
        $hash = $hashGenerator->hash($password);

        self::assertTrue($hashGenerator->validate($password, $hash));
    }
}
