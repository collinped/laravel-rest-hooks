<?php

namespace Collinped\LaravelRestHooks\Tests\TestClasses;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;

class TestClient
{
    private array $requests = [];

    private int $useResponseCode = 200;

    private bool $throwRequestException = false;

    private bool $throwConnectionException = false;

    public function request(string $method, string $url, array $options)
    {
        $this->requests[] = compact('method', 'url', 'options');

        if ($this->throwRequestException) {
            throw new RequestException(
                'Request failed exception',
                new Request($method, $url),
                new Response(500)
            );
        }

        if ($this->throwConnectionException) {
            throw new ConnectException(
                'Request timeout',
                new Request($method, $url),
            );
        }

        return new Response($this->useResponseCode);
    }

    public function assertRequestCount(int $expectedCount)
    {
        Assert::assertCount($expectedCount, $this->requests);

        return $this;
    }

    public function assertRequestsMade(array $expectedRequests)
    {
        $this->assertRequestCount(count($expectedRequests));

        foreach ($expectedRequests as $index => $expectedRequest) {
            foreach ($expectedRequest as $name => $value) {
                Assert::assertEquals($value, $this->requests[$index][$name]);
            }
        }
    }

    public function letEveryRequestFail()
    {
        $this->useResponseCode = 500;
    }

    public function letRequestReturnClosedResthook()
    {
        $this->useResponseCode = 410;
    }

    public function throwRequestException()
    {
        $this->throwRequestException = true;
    }

    public function throwConnectionException()
    {
        $this->throwConnectionException = true;
    }
}
