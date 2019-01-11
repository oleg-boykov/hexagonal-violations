<?php

namespace App\Domain\Model\Suggestion;

class Status
{
    const ACCEPTED = 'accepted';
    const UNPROCESSED = 'unprocessed';
    const REJECTED = 'rejected';
    const DELETED = 'deleted';

    private $statused = [
        0 => self::ACCEPTED,
        1 => self::UNPROCESSED,
        2 => self::REJECTED,
        3 => self::DELETED,
    ];
}