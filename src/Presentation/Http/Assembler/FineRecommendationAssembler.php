<?php

namespace App\Presentation\Http\Assembler;

use App\Domain\Model\FineRecommendation;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Presentation\Http\DTO\FineRecommendationDTO;
use App\Presentation\Http\DTO\ViolatorDTO;

class FineRecommendationAssembler
{
    private $supportRepository;

    /**
     * ViolationAssembler constructor.
     *
     * @param SupportRepositoryInterface $supportRepository
     */
    public function __construct(SupportRepositoryInterface $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    /**
     * @param FineRecommendation $recommendation
     * @return FineRecommendationDTO
     */
    public function convertToDto(FineRecommendation $recommendation): FineRecommendationDTO
    {
        $violatorId = $recommendation->getViolation()->getViolatorId();
        $support = $this->supportRepository->find($violatorId);
        $violatorDto = new ViolatorDTO($violatorId, $support ? $support->getFullName() : null);

        return new FineRecommendationDTO($recommendation, $violatorDto);
    }
}
