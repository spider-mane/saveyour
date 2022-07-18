<?php

namespace WebTheory\Saveyour\Data;

use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

class SimpleCacheDataManager implements FieldDataManagerInterface
{
    protected CacheInterface $cache;

    protected string $key;

    protected $ttl;

    public function __construct(CacheInterface $cache, string $key, $ttl = null)
    {
        $this->cache = $cache;
        $this->key = $key;
        $this->ttl = $ttl;
    }

    public function getCurrentData(ServerRequestInterface $request)
    {
        $data = $this->cache->get($this->key);

        $this->cache->delete($this->key);

        return $data;
    }

    public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    {
        $this->cache->set($this->key, $data, $this->ttl);

        return true;
    }
}
