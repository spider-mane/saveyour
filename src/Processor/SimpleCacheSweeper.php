<?php

namespace WebTheory\Saveyour\Processor;

use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;
use WebTheory\Saveyour\Report\FormProcessReport;

class SimpleCacheSweeper extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected CacheInterface $cache;

    /**
     * @var array<string>
     */
    protected array $keys;

    public function __construct(string $name, CacheInterface $cache, array $keys, ?array $fields = null)
    {
        parent::__construct($name, $fields);

        $this->cache = $cache;
        $this->keys = $keys;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        if ($this->allFieldsPresent($results)) {
            $status = $this->cache->deleteMultiple($this->keys);
        }

        return new FormProcessReport([
            'successful' => $status ?? null,
        ]);
    }
}
