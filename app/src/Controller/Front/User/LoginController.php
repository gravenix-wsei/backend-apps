<?php

namespace App\Controller\Front\User;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authenticator\JsonLoginAuthenticator;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly JsonLoginAuthenticator $jsonLoginAuthenticator
    )
    {
    }

    #[Route('/user/login', name: 'front.user.login', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $firewallName = 'api';

        if ($request->getMethod() === Request::METHOD_POST) {
            $jsonPayload = [
                'username' => $request->get('username'),
                'password' => $request->get('password'),
            ];
            $requestWithJson = new Request(
                request: $jsonPayload,
                content: \json_encode($jsonPayload)
            );
            $passport = $this->jsonLoginAuthenticator->authenticate($requestWithJson);
            $token = $this->jsonLoginAuthenticator->createToken($passport, $firewallName);
            $response = $this->jsonLoginAuthenticator->onAuthenticationSuccess($request, $token, $firewallName);
        } else {
            $response = null;
        }

        return $this->render(
        'front/user/login/index.html.twig',
            [],
            $response
        );
    }
}
