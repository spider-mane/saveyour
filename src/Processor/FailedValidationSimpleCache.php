<?php

namespace WebTheory\Saveyour\Processor;

use DateInterval;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;
use WebTheory\Saveyour\Report\FormProcessReport;

class FailedValidationSimpleCache extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected CacheInterface $cache;

    protected string $key;

    /**
     * @var null|int|DateInterval
     */
    protected $ttl;

    /**
     * @param string $name
     * @param CacheInterface $cache
     * @param string $key
     * @param null|int|DateInterval $ttl
     */
    public function __construct(string $name, CacheInterface $cache, string $key, $ttl = null)
    {
        parent::__construct($name, null);

        $this->cache = $cache;
        $this->key = $key;
        $this->ttl = $ttl;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        $violations = [];

        foreach ($results as $field => $result) {
            if (false === $result->validationStatus()) {
                $violations[$field] = $result->ruleViolations();
            }
        }

        if ($violations) {
            $status = $this->cache->set($this->key, $violations, $this->ttl);
        }

        return new FormProcessReport([
            'successful' => $status ?? null,
            'violations' => $violations,
        ]);
    }
}
