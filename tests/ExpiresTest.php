<?php

namespace Middlewares\Tests;

use Middlewares\Expires;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use mindplay\middleman\Dispatcher;

class ExpiresTest extends \PHPUnit_Framework_TestCase
{
    public function expiresProvider()
    {
        return [
            [
                'text/css',
                '',
                'max-age='.(strtotime('+1 year') - time()),
            ],
            [
                'text/css',
                'public',
                'public, max-age='.(strtotime('+1 year') - time()),
            ],
            [
                '',
                'public',
                'public, max-age='.(strtotime('+1 month') - time()),
            ],
            [
                '',
                'public, max-age=35',
                'public, max-age=35',
            ],
        ];
    }

    /**
     * @dataProvider expiresProvider
     */
    public function testExpires($contentType, $cacheControl, $result)
    {
        $response = (new Dispatcher([
            new Expires(),
            function () use ($contentType, $cacheControl) {
                return (new Response())
                    ->withHeader('Cache-Control', $cacheControl)
                    ->withHeader('Content-Type', $contentType);
            },
        ]))->dispatch(new Request('/', 'GET'));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($result, $response->getHeaderLine('Cache-Control'));
        $this->assertTrue($response->hasHeader('Expires'));
    }

    public function testNoExpires()
    {
        $response = (new Dispatcher([
            new Expires(),
            function () {
                return (new Response())
                    ->withHeader('Cache-Control', 'no-store');
            },
        ]))->dispatch(new Request('/', 'GET'));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertFalse($response->hasHeader('Expires'));

        $response = (new Dispatcher([
            new Expires(),
            function () {
                return new Response();
            },
        ]))->dispatch(new Request('/', 'POST'));

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertFalse($response->hasHeader('Expires'));
    }
}
