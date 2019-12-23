<?php

declare(strict_types=1);

namespace Infrasructure\ExternalService;

use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;

final class CachedClient implements Client
{
    /** @var Client */
    private $client;

    /** @var CacheItemPoolInterface */
    private $cache;

    /** @var int */
    private $cacheTtlInSeconds;

    public function __construct(Client $client, CacheItemPoolInterface $cache, int $cacheTtlInSeconds)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheTtlInSeconds = $cacheTtlInSeconds;
    }

    /**
     * @inheritdoc
     */
    public function get(Request $request): Response
    {
        try {
            $key = $this->getKey($request);

            $cachedResponse = $this->cache->getItem($key);
            if (!$cachedResponse->isHit()) {
                $cachedResponse->set($this->callClient($request))->expiresAfter($this->cacheTtlInSeconds);

                $this->cache->saveDeferred($cachedResponse);
            }

            return $cachedResponse->get();
        } catch (CacheException $e) {
            return $this->callClient($request);
        }
    }

    /**
     * @throws ClientError
     */
    private function callClient(Request $request): Response
    {
        return $this->client->get($request);
    }

    private function getKey(Request $request): string
    {
        // returns PSR-6 compatible key
    }
}