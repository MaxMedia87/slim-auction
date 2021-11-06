<?php

declare(strict_types=1);

namespace Test\Unit\Auth\Service;

use App\Auth\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testSuccess(): void
    {
        $interval = new \DateInterval('PT1H');
        $date = new \DateTimeImmutable('+1 day');

        $tokenGenerator = new TokenGenerator($interval);
        $token = $tokenGenerator->generate($date);

        self::assertEquals($date->add($interval), $token->expires());
    }
}
