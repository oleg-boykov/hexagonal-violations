<?php

namespace App\Presentation\Http\Controller;

use App\Application\RegisterSuggestionDTO;
use App\Application\RegisterViolationDTO;
use App\Application\RegistrationResultDTO;
use App\Application\ViolationRegistry;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;
use App\Presentation\Http\Assembler\FineRecommendationAssembler;
use App\Presentation\Http\Assembler\SuggestionAssembler;
use App\Presentation\Http\Assembler\ViolationAssembler;
use App\Presentation\Http\DTO\ResponseDTO;
use App\Presentation\Http\DTO\SuggestionDTO;
use App\Presentation\Http\DTO\ViolationQuery\QueryDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SuggestViolationController
{
    /**
     * @var ViolationRegistry
     */
    private $violationRegistry;

    /**
     * @var ViolationSuggestionRepositoryInterface
     */
    private $suggestionRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        ViolationRegistry $violationRegistry,
        ViolationSuggestionRepositoryInterface $suggestionRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->violationRegistry = $violationRegistry;
        $this->suggestionRepository = $suggestionRepository;
        $this->serializer = $serializer;
        $this->em = $em;
    }


    /**
     * @Route("/violation_suggestions", methods={"GET"}, name="get_violation_suggestions")
     * @param Request $request
     * @param SuggestionAssembler $assembler
     * @return JsonResponse
     */
    public function getViolationSuggestions(Request $request, SuggestionAssembler $assembler): JsonResponse
    {
        $queryDto = QueryDTO::fromRequest($request);
        $query = $queryDto->toQuery();

        $result = $this->suggestionRepository->findByQuery($query);

        $response = (new ResponseDTO())->setData(
            [
                'data' => [
                    'page' => $result->getQuery()->getPage(),
                    'total' => $result->getTotal(),
                    'violations' => $assembler->convertToDtos($result->getSuggestions()),
                ]
            ]
        );
        return JsonResponse::fromJsonString($this->serializer->serialize($response, 'json'));
    }

    /**
     * @Route("/violation_suggestions", methods={"POST"}, name="create_suggestion")
     */
    public function postSuggestion(
        Request $request,
        UserInterface $user,
        SuggestionAssembler $suggestionAssembler
    ): JsonResponse {
        /** @var RegisterSuggestionDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), RegisterSuggestionDTO::class, 'json');
        $dto->offeredBy = $user->getUsername();
        $result = $this->violationRegistry->suggest($dto);
        if ($result->hasErrors()) {
            return JsonResponse::fromJsonString(
                $this->serializer->serialize(
                    (new ResponseDTO())->setErrors($result->getErrors()),
                    'json'
                )
            );

        }
        $this->em->flush();
        $data = ['violation' => $suggestionAssembler->convertToDto($result->getSuggestion())];

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())
                    ->setData($data)
                    ->setAlerts(["Violation suggestion has been registered."])
                ,
                'json'
            )
        );
    }
}
