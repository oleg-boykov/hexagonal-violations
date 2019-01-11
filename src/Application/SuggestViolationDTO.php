<?php

namespace App\Application;

class SuggestViolationDTO
{
    public $managerId;
    public $violatorId;
    public $ruleId;
    public $victimId;
    public $victimType;
    public $comment;
}
