<?php

namespace App\Tests\Application;

use App\Application\RegisterViolationDTO;
use App\Application\ViolationRegistry;
use App\Domain\Model\QualityManager;
use App\Domain\Model\Rule;
use App\Domain\Model\Support;
use App\Domain\Model\Violation;
use App\Domain\Repository\QualityManagerRepositoryInterface;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;
use App\Domain\Repository\ViolationSuggestionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ViolationRegistryTest extends TestCase
{
    protected $managerRepo;
    protected $supportRepo;
    protected $ruleRepo;
    protected $violationRepo;
    protected $suggestsRepo;
    /**
     * @var ViolationRegistry
     */
    protected $registry;

    protected function setUp()
    {
        $this->managerRepo = $this->prophesize(QualityManagerRepositoryInterface::class);
        $this->supportRepo = $this->prophesize(SupportRepositoryInterface::class);
        $this->ruleRepo = $this->prophesize(RuleRepositoryInterface::class);
        $this->violationRepo = $this->prophesize(ViolationRepositoryInterface::class);
        $this->suggestsRepo = $this->prophesize(ViolationSuggestionRepositoryInterface::class);
        $this->registry = new ViolationRegistry(
            $this->managerRepo->reveal(),
            $this->supportRepo->reveal(),
            $this->ruleRepo->reveal(),
            $this->violationRepo->reveal(),
            $this->suggestsRepo->reveal()
        );
    }

    /**
     * @test
     */
    public function canRegisterViolation()
    {
        $this->managerRepo->find(1)->willReturn(new QualityManager(1));
        $this->supportRepo->find(2)->willReturn(new Support(2, 'John Snow'));
        $this->ruleRepo->find(1)->willReturn(new Rule(2, 'Oops'));
        $this->violationRepo->add(Argument::type(Violation::class))->shouldBeCalled();

        $dto = new RegisterViolationDTO(1, 2, 1, 1, 1, '');
        /** @var Violation $violation */
        $violation = $this->registry->register($dto);
        $this->assertEquals($violation->getViolatorId(), 2);
    }
}