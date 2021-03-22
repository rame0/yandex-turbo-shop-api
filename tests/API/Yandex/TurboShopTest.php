<?php

namespace rame0\API\Yandex\Tests;

use PHPUnit\Framework\TestCase;
use rame0\API\Yandex\TurboShop;

class TurboShopTest extends TestCase
{
    private TurboShop $_api;

    public function __construct()
    {
        parent::__construct();
        $this->_api = new TurboShop('123');
    }

    public function testAuthorisation()
    {
        // No Authorization header provided
        $this->assertSame($this->_api->isAuthorised(), false);

        // Correct Authorization header
        $_SERVER['HTTP_Authorization'] = '123';
        $this->assertSame($this->_api->isAuthorised(), true);

        // Incorrect Authorization header
        $_SERVER['HTTP_Authorization'] = '556286';
        $this->assertSame($this->_api->isAuthorised(), false);

    }

    public function testPostBody()
    {
        $_SERVER['HTTP_Authorization'] = '123';

        $body = $this->_api->parsePostBody('false');
        $this->assertEquals(false, $body);

        $body = $this->_api->parsePostBody();
        $this->assertEquals(null, $body);

        $body = $this->_api->parsePostBody("{'api':1, 'body':2}");
        $this->assertEquals(null, $body);

        $body = $this->_api->parsePostBody(json_encode(['api' => 1, 'body' => 2]));
        $this->assertEquals(['api' => 1, 'body' => 2], $body);
    }
}
