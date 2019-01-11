<?php

namespace App\Tests\Model;

use App\Domain\Model\QualityManager;
use App\Domain\Model\Rule;
use App\Domain\Model\Support;
use App\Domain\Model\Victim;
use App\Domain\Model\VictimType;
use App\Domain\Model\Violation;
use PHPUnit\Framework\TestCase;

class ViolationTest extends TestCase
{
    /**
     * @var Violation
     */
    private $violation;

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $this->violation = new Violation(
            2,
            new Victim(1, VictimType::Order()),
            new Rule(1, 'Oops'),
            1,
            ''
        );
    }


    /**
     * @test
     * @throws \Exception
     */
    public function canResolveUnresolve()
    {
        $this->assertFalse($this->violation->isResolved());
        $this->violation->resolve();
        $this->assertTrue($this->violation->isResolved());
        $this->violation->unresolve();
        $this->assertFalse($this->violation->isResolved());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function canMarkAsSeen()
    {
        $this->assertNull($this->violation->getSeenAt());
        $this->violation->markSeen();
        $this->assertTrue($this->violation->getSeenAt() instanceof \DateTimeImmutable);
    }
}
