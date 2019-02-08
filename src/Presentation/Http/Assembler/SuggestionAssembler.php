<?php

namespace App\Presentation\Http\Assembler;

use App\Domain\Model\Suggestion\ViolationSuggestion;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Presentation\Http\DTO\SuggestionDTO;
use App\Presentation\Http\DTO\ViolatorDTO;

class SuggestionAssembler
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
     * @param iterable $suggestions
     *
     * @return array
     */
    public function convertToDtos(iterable $suggestions): array
    {
        $ids = [];
        /** @var ViolationSuggestion $suggestion */
        foreach ($suggestions as $suggestion) {
             $ids[] = $suggestion->getViolatorId();
             $ids[] = $suggestion->getOfferedBy();
        }
        $supports = $this->supportRepository->findByIds($ids);
        $dtos = [];
        foreach ($suggestions as $suggestion) {
            $dtos[] = new SuggestionDTO(
                $suggestion,
                $supports[$suggestion->getViolatorId()] ?? null,
                $supports[$suggestion->getOfferedBy()] ?? null
            );
        }

        return $dtos;
    }

    /**
     * @param ViolationSuggestion $suggestion
     *
     * @return SuggestionDTO
     */
    public function convertToDto(ViolationSuggestion $suggestion): SuggestionDTO
    {
        $supports = $this->supportRepository->findByIds([
            $suggestion->getViolatorId(), $suggestion->getOfferedBy()
        ]);
        return new SuggestionDTO(
            $suggestion,
            $supports[$suggestion->getViolatorId()] ?? null,
            $supports[$suggestion->getOfferedBy()] ?? null
        );
    }
}
