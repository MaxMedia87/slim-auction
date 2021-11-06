<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\Service\PasswordHashGenerator;
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
}
