<?php

declare(strict_types=1);

namespace Test\Http;

use App\Http\JsonResponse;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * @param mixed $data
     * @param int $status
     * @param string $expectedResult
     * @param int $expectedCode
     * @dataProvider createDataProvider
     */
    public function testItCanCreated(
        string $expectedResult,
        int $expectedCode,
        $data,
        int $status = StatusCodeInterface::STATUS_OK
    ): void {
        $response = new JsonResponse($data, $status);

        self::assertEquals($expectedResult, $response->getBody()->getContents());
        self::assertEquals($expectedCode, $response->getStatusCode());
    }

    /**
     * @return \Iterator<array>
     */
    public function createDataProvider(): \Iterator
    {
        yield [
            '1',
            200,
            1,
        ];

        $object = new \stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        yield [
            '{"str":"value","int":1,"none":null}',
            201,
            $object,
            StatusCodeInterface::STATUS_CREATED,
        ];
    }
}
