<?php

namespace App\Entrypoint\Controller\Dashboard\Client;

use App\Dashboard\Client\Application\Register\RegisterClientWithApplication\RegisterClientWithApplicationCommand;
use App\Shared\Infrastructure\Symfony\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class ApplicationRegisterClientController extends BaseController
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        return $this->json(
            $this->dispatch(new RegisterClientWithApplicationCommand(
                $data->name,
                $data->surname,
                $data->email,
                $data->password,
                $data->repeatedPassword,
                $data->secondSurname
            )),
            JsonResponse::HTTP_OK
        );
    }
}
