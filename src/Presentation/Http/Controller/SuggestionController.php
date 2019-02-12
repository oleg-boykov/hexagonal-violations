<?php

namespace App\Presentation\Http\Controller;

use App\Application\Command\CreateSuggestionCommand;
use App\Application\Command\UpdateSuggestionCommand;
use App\Presentation\Http\Assembler\SuggestionAssembler;
use App\Presentation\Http\DTO\ResponseDTO;
use App\Presentation\Http\DTO\UpdateSuggestionDTO;
use App\Presentation\Http\DTO\ViolationQuery\QueryDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SuggestionController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var MessageBusInterface
     */
    private $queryBus;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        SerializerInterface $serializer,
        MessageBusInterface $commandBus,
        MessageBusInterface $queryBus,
        EntityManagerInterface $em
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
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
        $query = $queryDto->toSuggestionsQuery();

        $result = $this->queryBus->dispatch($query);

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
        /** @var CreateSuggestionCommand $command */
        $command = $this->serializer->deserialize($request->getContent(), CreateSuggestionCommand::class, 'json');
        $command->setOfferedBy($user->getUsername());
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

    /**
     * @Route("/violation_suggestions/{id<\d+>}", methods={"PUT"}, name="update_suggestion")
     */
    public function updateSuggestion(Request $request, ?int $id, SuggestionAssembler $assembler): JsonResponse
    {
        /** @var UpdateSuggestionCommand $command */
        $command = $this->serializer->deserialize($request->getContent(), UpdateSuggestionCommand::class, 'json');
        $command->setId($id);
        $suggestion = $this->commandBus->dispatch($command);

        if (!$suggestion) {
            throw new NotFoundHttpException();
        }

        $this->em->flush();

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                (new ResponseDTO())
                    ->setData(['violation' => $assembler->convertToDto($suggestion)])
                    ->setAlerts(['Violation has been successfully updated']),
                'json'
            )
        );
    }
}
