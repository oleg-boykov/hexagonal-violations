<?php

namespace App\Tests\Model;

use App\Domain\Model\QualityManager;
use App\Domain\Model\Rule;
use App\Domain\Model\Support;
use App\Domain\Model\Victim;
use App\Domain\Model\VictimType;
use PHPUnit\Framework\TestCase;

class QualityManagerTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function canRegisterViolation()
    {
        $manager = new QualityManager(1);
        $violator = new Support(2, 'John Snow');
        $victim = new Victim(1, VictimType::Order());
        $rule = new Rule(1, 'Some rule');

        $violation = $manager->registerViolation($violator, $victim, $rule, 'hello');
        $this->assertEquals($violation->getComment(), 'hello');
        $this->assertEquals($violation->getViolatorId(), 2);
    }
}
