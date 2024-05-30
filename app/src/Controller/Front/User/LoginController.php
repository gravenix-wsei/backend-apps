<?php

namespace App\Controller\Front\User;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManager;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;
use Symfony\Component\Security\Http\Authenticator\JsonLoginAuthenticator;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthenticatorManagerInterface $authenticatorManager,
        private readonly JsonLoginAuthenticator $jsonLoginAuthenticator
    )
    {
    }

    #[Route('/user/login', name: 'front.user.login', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $messages = [];
        if ($request->getMethod() === Request::METHOD_POST) {
            $requestWithJson = $this->prepareJsonRequestWithCredentials($request);
            $response = $this->authenticatorManager->authenticateRequest($requestWithJson);
            if ($response->getStatusCode() === 401) {
                $messages[] = ['type' => 'error', 'message' => 'Incorrect username or password'];
            } elseif ($response->getStatusCode() === 200) {
                return new Response(
                    '',
                    302,
                    array_merge($response->headers->all(), [
                        'Location' => $this->redirectToRoute('front.home')->getTargetUrl(),
                    ])
                );
            }
        } else {
            $response = null;
        }

        return $this->render(
        'front/user/login/index.html.twig',
            [
                'messages' => $messages
            ],
            $response
        );
    }

    private function prepareJsonRequestWithCredentials(Request $request): Request
    {
        $jsonPayload = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];
        $requestWithJson = new Request(
            request: $jsonPayload,
            attributes: [
                '_security_authenticators' => [$this->jsonLoginAuthenticator],
            ],
            server: [
                'REQUEST_URI' => '/api/user/login',
            ],
            content: \json_encode($jsonPayload)
        );
        $requestWithJson->setRequestFormat('application/json');
        return $requestWithJson;
    }
}
