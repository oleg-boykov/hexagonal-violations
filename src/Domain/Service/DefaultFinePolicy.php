<?php

namespace App\Domain\Service;

use App\Domain\Model\FineRecommendation;
use App\Domain\Model\Support;
use App\Domain\Model\Violation;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;

class DefaultFinePolicy implements FinePolicyInterface
{
    const VIOLATION_PERIOD_DAYS = 30;

    private $violationRepo;
    private $supportRepo;

    public function __construct(ViolationRepositoryInterface $violationRepository, SupportRepositoryInterface $supportRepository)
    {
        $this->violationRepo = $violationRepository;
        $this->supportRepo = $supportRepository;
    }

    public function getFineRecommendation(Violation $violation): ?FineRecommendation
    {
        $totalViolations = $this->violationRepo->countLastViolationsForViolator(
            $violation->getViolatorId(),
            (new \DateTimeImmutable())->modify(sprintf("-%d days", self::VIOLATION_PERIOD_DAYS))
        );

        $repeatedViolations = $this->violationRepo->findRepeatedForViolator($violation->getRule(), $violation->getViolatorId());

        /** @var Support $violator */
        $violator = $this->supportRepo->find($violation->getViolatorId());

        $rule = $violation->getRule();
        if (count($repeatedViolations) >= $rule->getCritical()) {
            $fineAmount = $rule->getWorkingHours() ?: $violator->getHourlyWage() * $rule->getFinePercent() / 100;
            return new FineRecommendation($violation, $repeatedViolations, $totalViolations, $fineAmount);
        }

        return null;
    }
}
