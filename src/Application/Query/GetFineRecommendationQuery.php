<?php

namespace App\Application\Query;

use App\Domain\Model\Violation;

final class GetFineRecommendationQuery
{
    private $violation;

    /**
     * GetFineRecommendationQuery constructor.
     * @param Violation $violation
     */
    public function __construct(Violation $violation)
    {
        $this->violation = $violation;
    }

    /**
     * @return Violation
     */
    public function getViolation(): Violation
    {
        return $this->violation;
    }
}
