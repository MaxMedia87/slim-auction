<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\Service\PasswordHashGenerator;
use App\Auth\Service\TokenGenerator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param string $passwordHash
     * @param Token|null $joinConfirmToken
     * @dataProvider validCreationDataProvider
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress RedundantCondition
     */
    public function testCreatedAndReturnTheCorrectData(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        ?Token $joinConfirmToken
    ): void {
        $user = new User($id, $date, $email, $passwordHash, $joinConfirmToken);

        self::assertEquals($id->value(), $user->id()->value());
        self::assertInstanceOf(DateTimeImmutable::class, $user->date());
        self::assertEquals($email->value(), $user->email()->value());
        self::assertEquals($passwordHash, $user->passwordHash());

        if (null !== $joinConfirmToken) {
            self::assertEquals($joinConfirmToken->value(), $user->joinConfirmToken()->value());
        } else {
            self::assertNull($joinConfirmToken);
        }

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
    }

    /**
     * @return \Iterator<array>
     */
    public function validCreationDataProvider(): \Iterator
    {
        $hashGenerator = new PasswordHashGenerator(16);
        $date = new DateTimeImmutable();

        yield [
            Id::generate(),
            $date,
            new Email('test@mail.ru'),
            $hashGenerator->hash('pass'),
            new Token('8b979a49-045e-4af0-8fd5-d50cbf7cf705', $date)
        ];

        yield [
            Id::generate(),
            $date,
            new Email('test@mail.ru'),
            $hashGenerator->hash('pass'),
            null
        ];
    }

    /**
     * @param string $token
     * @param DateTimeImmutable $newDate
     * @dataProvider confirmDataProvider
     */
    public function testConfirm(
        string $token,
        DateTimeImmutable $newDate
    ): void {
        $hashGenerator = new PasswordHashGenerator(16);
        $tokenDate = new DateTimeImmutable('+1 day');

        $user = new User(
            Id::generate(),
            new DateTimeImmutable(),
            new Email('test@mail.ru'),
            $hashGenerator->hash('pass'),
            new Token('8b979a49-045e-4af0-8fd5-d50cbf7cf705', $tokenDate)
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->confirmJoin($token, $newDate);

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());
    }

    /**
     * @return \Iterator<array>
     */
    public function confirmDataProvider(): \Iterator
    {
        $date = new DateTimeImmutable();
        $value = '8b979a49-045e-4af0-8fd5-d50cbf7cf705';

        $token = new Token($value, $date->modify('-1 day'));
        yield [
            $token->value(),
            $token->expires()
        ];
    }

    public function testExceptionIfNoConfirmationIsRequired(): void
    {
        $hashGenerator = new PasswordHashGenerator(16);
        $tokenGenerator = new TokenGenerator(new \DateInterval('PT1H'));
        $token = $tokenGenerator->generate(new DateTimeImmutable('+1 day'));

        $user = new User(
            Id::generate(),
            new DateTimeImmutable(),
            new Email('test@mail.ru'),
            $hashGenerator->hash('pass'),
            $token
        );

        $user->confirmJoin($token->value(), new DateTimeImmutable());

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Подтверждение не требуется.');

        $user->confirmJoin($token->value(), new DateTimeImmutable());
    }
}
