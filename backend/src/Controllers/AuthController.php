<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Core/Request.php';
require_once dirname(__DIR__) . '/Core/Response.php';
require_once dirname(__DIR__) . '/Services/AuthService.php';

class AuthController
{
    public function __construct(private readonly AuthService $service)
    {
    }

    public function register(Request $request): Response
    {
        try {
            return Response::json(['data' => $this->service->register($request->input())], 201);
        } catch (InvalidArgumentException $exception) {
            return Response::json(['error' => $exception->getMessage()], 422);
        } catch (DomainException $exception) {
            return Response::json(['error' => $exception->getMessage()], 409);
        }
    }

    public function login(Request $request): Response
    {
        try {
            return Response::json(['data' => $this->service->login($request->input())]);
        } catch (DomainException $exception) {
            return Response::json(['error' => $exception->getMessage()], 401);
        }
    }

    public function me(Request $request, array $auth): Response
    {
        return Response::json(['data' => $auth]);
    }
}
