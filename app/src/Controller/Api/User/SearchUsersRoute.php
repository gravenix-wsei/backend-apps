<?php

namespace App\Controller\Api\User;

use App\Core\Content\Response\User\UserSearchResultResponse;
use App\Core\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchUsersRoute extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ){
    }
    #[Route('/api/user/search', name: 'api.user.search', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $users = $this->userService->searchUsers($request->request->all());

        return new UserSearchResultResponse($users);
    }
}
