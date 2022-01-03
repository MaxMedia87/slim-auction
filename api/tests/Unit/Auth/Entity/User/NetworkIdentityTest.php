<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Entity\User;

use App\Auth\Entity\User\NetworkIdentity;
use Iterator;
use PHPUnit\Framework\TestCase;

class NetworkIdentityTest extends TestCase
{
    /**
     * @param string $identity
     * @param string $network
     * @dataProvider networkDataProvider
     */
    public function testCreatedAndReturnTheCorrectData(
        string $identity,
        string $network
    ): void {
        $networkIdentity = new NetworkIdentity($identity, $network);

        self::assertEquals($identity, $networkIdentity->identity());
        self::assertEquals($network, $networkIdentity->network());
    }

    /**
     * @return Iterator<array>
     */
    public function networkDataProvider(): Iterator
    {
        yield ['00032', 'vk'];
    }

    /**
     * @param string $identity
     * @param string $network
     * @param bool $expectedResult
     * @dataProvider networkComparisonDataProvider
     */
    public function testIsEqualTo(
        string $identity,
        string $network,
        bool $expectedResult
    ): void {
        $networkIdentity = new NetworkIdentity('00001', 'vk');
        $newNetworkIdentity = new NetworkIdentity($identity, $network);

        self::assertSame($expectedResult, $networkIdentity->isEqualTo($newNetworkIdentity));
    }

    /**
     * @return Iterator<array>
     */
    public function networkComparisonDataProvider(): Iterator
    {
        yield ['00001', 'vk', true];
        yield ['000032', 'vk', false];
        yield ['00001', 'fb', false];
    }

    /**
     * @param string $identity
     * @param string $network
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @psalm-param  class-string<\Throwable> $exceptionClass
     * @dataProvider invalidNetworkDataProvider
     */
    public function testItCanThrowExceptionIfDataIsEmpty(
        string $identity,
        string $network,
        string $exceptionClass,
        string $exceptionMessage
    ): void {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        new NetworkIdentity($identity, $network);
    }

    /**
     * @return Iterator<array>
     */
    public function invalidNetworkDataProvider(): Iterator
    {
        yield [
            '',
            'vk',
            \InvalidArgumentException::class,
            'ID социальной сети не должен быть пустым.'
        ];

        yield [
            '0001',
            '',
            \InvalidArgumentException::class,
            'Название социальной сети не должен быть пустым.'
        ];
    }
}
