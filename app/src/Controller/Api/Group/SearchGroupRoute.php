<?php

namespace App\Controller\Api\Group;

use App\Core\Content\Response\Group\GroupSearchResultResponse;
use App\Core\Service\Group\GroupServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
}
