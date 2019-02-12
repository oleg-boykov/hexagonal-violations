<?php

namespace App\Infrastructure\Repository\External;

use App\Domain\Model\Support;
use App\Domain\Repository\SupportRepositoryInterface;
use Psr\SimpleCache\CacheInterface;

class SupportRepository implements SupportRepositoryInterface
{
    private $supports = [];

    private $cache;

    private $supportsApiUrl;

    public function __construct(CacheInterface $cache, string $supportsApiUrl)
    {
        $this->cache = $cache;
        $this->supportsApiUrl = $supportsApiUrl;
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function init()
    {
        if (!$this->cache->has('support_list')) {
            $response = file_get_contents($this->supportsApiUrl);
            $response = json_decode($response, true);
            $supports = [];
            foreach ($response['data'] as $row) {
                $supports[$row['id']] = new Support($row['id'], $row['full_name'], $row['hourlyWage']);
            }
            $this->cache->set('support_list', $supports);
        }
        $this->supports = $this->cache->get('support_list');
    }

    public function find(int $id): ?Support
    {
        if (!$this->supports) {
            $this->init();
        }

        return $this->supports[$id] ?? null;
    }

    public function findByIds(array $ids): array
    {
        if (!$this->supports) {
            $this->init();
        }

        return array_intersect_key($this->supports, array_flip($ids));
    }
}
