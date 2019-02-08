<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\Support;
use App\Domain\Repository\SupportRepositoryInterface;

class SupportRepository implements SupportRepositoryInterface
{
    private $supports = [
        1 => 'Unknown',
        2 => 'John Snow',
    ];

    public function find(int $id): ?Support
    {
        return isset($this->supports[$id]) ? new Support($id, $this->supports[$id], 1) : null;
    }

    public function findByIds(array $ids): array
    {
        $result = [];
        foreach ($ids as $id) {
            $support = isset($this->supports[$id]) ? new Support($id, $this->supports[$id], 1) : null;
            if ($support) {
                $result[$id] = $support;
            }
        }

        return $result;
    }
}
