<?php

namespace App\Presentation\Http\DTO;

class ViolatorDTO
{
    public $id;
    public $fullName;

    public function __construct(int $id, ?string $fullName = null)
    {
        $this->id = $id;
        $this->fullName = $fullName ?: "Unknown";
    }
}
