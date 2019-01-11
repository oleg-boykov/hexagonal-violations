<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\Support;
use App\Domain\Repository\SupportRepositoryInterface;

class SupportRepository implements SupportRepositoryInterface
{
    private $supports = [
        2 => 'John Show'
    ];

    public function find(int $id): ?Support
    {
        return new Support($id, "Unknown", 1);
        return isset($this->supports[$id]) ? new Support($id, $this->supports[$id], 1) : null;
    }

    public function findByIds(array $ids): array
    {
        return [];
    }
}
