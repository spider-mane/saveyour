<?php

use GuzzleHttp\Psr7\ServerRequest;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Request;

class RequestTest extends TestCase
{
    public function testCanFindPostVar()
    {
        $param = 'foo';
        $value = 'bar';

        $request = ServerRequest::fromGlobals()
            ->withMethod('POST')
            ->withParsedBody([
                $param => $value
            ]);

        $this->assertTrue(Request::has($request, $param));
        $this->assertEquals($value, Request::var($request, $param));
    }

    public function testCanFindGetVar()
    {
        $param = 'foo';
        $value = 'bar';

        $request = ServerRequest::fromGlobals()
            ->withMethod('GET')
            ->withQueryParams([
                $param => $value
            ]);

        $this->assertTrue(Request::has($request, $param));
        $this->assertEquals($value, Request::var($request, $param));
    }

    public function testCanFindMiscVar()
    {
        $param = 'foo';
        $value = 'bar';

        $request = ServerRequest::fromGlobals()
            ->withMethod('CUSTOM')
            ->withParsedBody([
                $param => $value
            ]);

        $this->assertTrue(Request::has($request, $param));
        $this->assertEquals($value, Request::var($request, $param));
    }

    public function testCanFindAttribute()
    {
        $param = 'foo';
        $value = 'bar';

        $request = ServerRequest::fromGlobals()
            ->withAttribute($param, $value);

        $this->assertTrue(Request::hasAttr($request, $param));
        $this->assertFalse(Request::hasAttr($request, 'fail'));
        $this->assertEquals($value, Request::attr($request, $param));
    }
}
