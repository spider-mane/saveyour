<?php

namespace WebTheory\Saveyour;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    /**
     *
     */
    public static function var(ServerRequestInterface $request, $param)
    {
        $request = static::getArgs($request);

        return $request[$param] ?? null;
    }

    /**
     *
     */
    public static function has(ServerRequestInterface $request, $param): bool
    {
        $request = static::getArgs($request);

        return isset($request[$param]);
    }

    /**
     *
     */
    public static function attr(ServerRequestInterface $request, $attribute)
    {
        return $request->getAttribute($attribute);
    }

    /**
     *
     */
    public static function hasAttr(ServerRequestInterface $request, $attribute): bool
    {
        $attributes = $request->getAttributes();

        return isset($attributes[$attribute]);
    }

    /**
     *
     */
    public static function getArgs(ServerRequestInterface $request): array
    {
        switch ($request->getMethod()) {
            case 'GET':
                $request = $request->getQueryParams();

                break;

            case 'POST':
                $request = $request->getParsedBody();

                break;

            default:
                $request = $request->getParsedBody();

                break;
        }

        return (array) $request;
    }

    /**
     *
     */
    public static function getParams(ServerRequestInterface $request): array
    {
        return array_keys(static::getArgs($request));
    }
}
