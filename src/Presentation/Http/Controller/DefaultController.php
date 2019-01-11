<?php

namespace App\Presentation\Http\Controller;

use App\Application\RegisterViolationDTO;
use App\Application\ViolationRegistry;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;
use App\Presentation\Http\Assembler\FineRecommendationAssembler;
use App\Presentation\Http\Assembler\ViolationAssembler;
use App\Presentation\Http\DTO\FineRecommendationDTO;
use App\Presentation\Http\DTO\ResponseDTO;
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

class DefaultController
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var ViolationRepositoryInterface
     */
    private $violationRepository;

    /**
     * @var ViolationRegistry
     */
    private $violationRegistry;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        ViolationRegistry $violationRegistry,
        ViolationRepositoryInterface $violationRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->violationRegistry = $violationRegistry;
        $this->violationRepository = $violationRepository;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @Route("/ping", methods={"GET"}, name="ping")
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(['ping' => 'pong']);
    }

    /**
     * @Route("/violations/rules", methods={"GET"}, name="get_rules")
     */
    public function getRules(): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())->setData(['support_rules' => $this->ruleRepository->findAll()]),
                'json'
            )
        );
    }

    /**
     * @Route("/violations", methods={"GET"}, name="get_violations")
     * @param Request $request
     * @param ViolationAssembler $assembler
     * @return JsonResponse
     */
    public function getViolations(Request $request, ViolationAssembler $assembler, ValidatorInterface $validator): JsonResponse
    {
        $queryDto = QueryDTO::fromRequest($request);
        $query = $queryDto->toQuery();

        $result = $this->violationRepository->findByQuery($query);

        $response = (new ResponseDTO())->setData(
            [
                'data' => [
                    'page' => $result->getQuery()->getPage(),
                    'total' => $result->getTotal(),
                    'violations' => $assembler->convertToDtos($result->getViolations()),
                ]
            ]
        );
        return JsonResponse::fromJsonString($this->serializer->serialize($response, 'json'));
    }

    /**
     * @Route("/violations", methods={"POST"}, name="create_violation")
     */
    public function postViolation(
        Request $request,
        UserInterface $user,
        FineRecommendationAssembler $fileAssembler,
        ViolationAssembler $violationAssembler
    ): JsonResponse {
        /** @var RegisterViolationDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), RegisterViolationDTO::class, 'json');
        $dto->qualityManagerId = $user->getUsername();
        $result = $this->violationRegistry->register($dto);
        if ($result->hasErrors()) {
            return JsonResponse::fromJsonString(
                $this->serializer->serialize(
                    (new ResponseDTO())->setErrors($result->getErrors()),
                    'json'
                )
            );

        }
        $this->em->flush();
        $recommendation = $this->violationRegistry->getFineRecommendation($result->getViolation());
        if ($recommendation) {
            $data = ['fine' => $fileAssembler->convertToDto($recommendation)];
        } else {
            $data = ['violation' => $violationAssembler->convertToDto($result->getViolation())];
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())
                    ->setData($data)
                    ->setAlerts(["Violation has been registered."])
                ,
                'json'
            )
        );
    }

    /**
     * @Route("/violations/{id<\d+>}", methods={"PUT"}, name="resolve_violation")
     */
    public function resolveViolation(Request $request, ?int $id, ViolationAssembler $assembler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $violation = $this->violationRepository->find($id);
        if (!$violation) {
            throw new NotFoundHttpException();
        }
        if (isset($data['resolved'])) {
            if ($data['resolved'] == 1) {
                $violation->resolve();
            } else {
                $violation->unresolve();
            }
            $this->em->flush();
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())->setData(['violation' => $assembler->convertToDto($violation)]),
                'json'
            )
        );
    }

    /**
     * @Route("/violations/{id<\d+>}", methods={"DELETE"}, name="delete_violation")
     */
    public function deleteViolation(?int $id): JsonResponse
    {
        $this->violationRegistry->delete($id);
        $this->em->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())->setData(['violation' => ['violation_id' => $id]]),
                'json'
            )
        );
    }
}
