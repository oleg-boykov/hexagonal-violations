<?php

namespace App\Presentation\Http\Assembler;

use App\Domain\Model\Violation;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Presentation\Http\DTO\ViolationDTO;
use App\Presentation\Http\DTO\ViolatorDTO;

class ViolationAssembler
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
     * @param iterable $violations
     *
     * @return array
     */
    public function convertToDtos(iterable $violations): array
    {
        $ids = [];
        /** @var Violation $violation */
        foreach ($violations as $violation) {
             $ids[] = $violation->getViolatorId();
        }
        $supports = $this->supportRepository->findByIds($ids);
        $dtos = [];
        foreach ($violations as $violation) {
            $violatorId = $violation->getViolatorId();
            $violatorDto = new ViolatorDTO(
                $violatorId,
                isset($supports[$violatorId]) ? $supports[$violatorId]->getFullName() : null
            );
            $dtos[] = new ViolationDTO($violation, $violatorDto);
        }

        return $dtos;
    }

    /**
     * @param Violation $violation
     *
     * @return ViolationDTO
     */
    public function convertToDto(Violation $violation): ViolationDTO
    {
        $support = $this->supportRepository->find($violation->getViolatorId());
        return new ViolationDTO(
            $violation,
            new ViolatorDTO($violation->getViolatorId(),  $support ? $support->getFullName() : null)
        );
    }
}
