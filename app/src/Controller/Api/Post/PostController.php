<?php

namespace App\Controller\Api\Post;

use App\Core\Content\Response\AbstractApiResponse;
use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Core\Service\Post\PostServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PostController extends AbstractController
{
    public function __construct(
        private readonly PostServiceInterface $postService,
        private readonly GroupServiceInterface $groupService
    ) {
    }

    #[Route('/api/group/post/{groupId}', name: 'app_api_group_post')]
    public function index(Uuid $groupId, Request $request, Security $security): AbstractApiResponse
    {
        $user = $this->getUserFromSecurity($security);
        $group = $this->groupService->getGroup($groupId);
        $content = $request->get('content');
        if (empty($content)) {
            throw new BadRequestHttpException('Missing content');
        }

        if (!$this->postService->createPost($user, $group, $content)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
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
