<?php

namespace WebTheory\Saveyour\Enum;

use MyCLabs\Enum\Enum;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Http\Request;

/**
 * @method static self Request()
 * @method static self Attribute()
 */
final class ServerRequestLocation extends Enum
{
    private const Request = 'request';
    private const Attribute = 'attribute';

    public function lookup(string $param, ServerRequestInterface $request)
    {
        switch ($param) {
            case self::Request:
                return Request::var($request, $param);

            case self::Attribute:
                return Request::attr($request, $param);
        }
    }
}
