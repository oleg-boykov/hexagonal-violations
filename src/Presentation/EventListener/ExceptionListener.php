<?php
namespace App\Presentation\EventListener;

use App\Presentation\Http\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionListener
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $message = sprintf(
            'Error: %s, code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        }
        $response->setStatusCode($statusCode);
        $response->setContent(
            $this->serializer->serialize((new ResponseDTO())->addError($message)->setStatus($statusCode), 'json')
        );

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
