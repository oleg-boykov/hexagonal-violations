<?php

namespace App\Presentation\Http\Controller;

use App\Application\Command\CreateViolationCommand;
use App\Application\Command\DeleteViolationCommand;
use App\Application\Command\ResolveViolationCommand;
use App\Application\Query\GetAllRulesQuery;
use App\Application\Query\GetFineRecommendationQuery;
use App\Presentation\Http\Assembler\FineRecommendationAssembler;
use App\Presentation\Http\Assembler\ViolationAssembler;
use App\Presentation\Http\DTO\ResponseDTO;
use App\Presentation\Http\DTO\ViolationQuery\QueryDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ViolationController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var MessageBusInterface
     */
    private $queryBus;

    public function __construct(
        MessageBusInterface $commandBus,
        MessageBusInterface $queryBus,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->serializer = $serializer;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
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
                (new ResponseDTO())->setData(['support_rules' => $this->queryBus->dispatch(new GetAllRulesQuery())]),
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
    public function getViolations(Request $request, ViolationAssembler $assembler): JsonResponse
    {
        $queryDto = QueryDTO::fromRequest($request);
        $query = $queryDto->toViolationsQuery();

        $result = $this->queryBus->dispatch($query);

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
        FineRecommendationAssembler $fineAssembler,
        ViolationAssembler $violationAssembler
    ): JsonResponse {
        /** @var CreateViolationCommand $command */
        $command = $this->serializer->deserialize($request->getContent(), CreateViolationCommand::class, 'json');
        $command->setQualityManager($user->getUsername());
        $result = $this->commandBus->dispatch($command);
        if ($result->hasErrors()) {
            return JsonResponse::fromJsonString(
                $this->serializer->serialize(
                    (new ResponseDTO())->setErrors($result->getErrors()),
                    'json'
                )
            );

        }
        $this->em->flush();
        $recommendation = $this->queryBus->dispatch(new GetFineRecommendationQuery($result->getViolation()));
        if ($recommendation) {
            $data = ['fine' => $fineAssembler->convertToDto($recommendation)];
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

        $violation = $this->commandBus->dispatch(new ResolveViolationCommand($id, $data['resolved'] == 1));
        if (!$violation) {
            throw new NotFoundHttpException();
        }

        $this->em->flush();

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
        $this->commandBus->dispatch(new DeleteViolationCommand($id));
        $this->em->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())->setData(['violation' => ['violation_id' => $id]]),
                'json'
            )
        );
    }
}
