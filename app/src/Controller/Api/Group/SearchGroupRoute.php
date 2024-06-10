<?php

namespace App\Controller\Api\Group;

use App\Core\Content\Response\Group\GroupSearchResultResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

class SearchGroupRoute extends AbstractController
{
    public function __construct(
        private readonly GroupServiceInterface $groupService
    ) {
    }

    #[Route('/api/group/search', name: 'api.group.search', methods: ['POST'])]
    public function index(Request $request): GroupSearchResultResponse
    {
        $foundGroups = $this->groupService->searchGroup($request->request->all());

        return new GroupSearchResultResponse($foundGroups);
    }

    #[Route('/api/groups', name: 'api.group', methods: ['GET'])]
    public function listGroups(Security $security): GroupSearchResultResponse
    {
        $user = $this->getUserFromSecurity($security);
        $groups = $this->groupService->getUserGroups($user);

        return new GroupSearchResultResponse($groups);
    }

    private function getUserFromSecurity(Security $security): User
    {
        $user = $security->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('Invalid user');
        }

        return $user;
    }
}
