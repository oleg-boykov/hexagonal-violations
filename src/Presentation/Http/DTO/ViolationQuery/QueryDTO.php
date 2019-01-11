<?php

namespace App\Presentation\Http\DTO\ViolationQuery;

use App\Domain\Query\ViolationQuery;
use Symfony\Component\HttpFoundation\Request;

final class QueryDTO
{
    public $page;
    public $perPage;
    /**
     * @var FiltersDTO
     */
    public $filters;

    public static function fromRequest(Request $request): self
    {
        $dto = new self();
        $dto->page = (int) $request->query->get('page', 1);
        $dto->perPage = (int) $request->query->get('perPage', 50);
        $filters = $request->query->get('filters', []);
        $filtersDto = new FiltersDTO();
        if ($filters) {
            $filtersDto->rule = isset($filters['rule']) ? (int) $filters['rule'] : null;
            $filtersDto->violator = isset($filters['violator']) ? (int) $filters['violator'] : null;
            $filtersDto->startDate = $filters['start_date'] ?? null;
            $filtersDto->endDate = $filters['end_date'] ?? null;
        }
        $dto->filters = $filtersDto;

        return $dto;
    }

    public function toQuery(): ViolationQuery
    {
        $filters = $this->filters;
        $startDate = !empty($filters->startDate) ? \DateTime::createFromFormat('U', $filters->startDate) : null;
        $endDate = !empty($filters->endDate) ? \DateTime::createFromFormat('U', $filters->endDate) : null;
        return new ViolationQuery(
            $this->page,
            $this->perPage,
            $this->filters->rule,
            $this->filters->violator,
            $startDate ?? null,
            $endDate ?? null
        );
    }
}
