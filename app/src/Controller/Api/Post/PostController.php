<?php

namespace App\Controller\Api\Post;

use App\Core\Content\Response\AbstractApiResponse;
use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\Post\PostResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Core\Service\Post\PostServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/api/group/post/{groupId}', name: 'api.group.post.create', methods: ['POST'])]
    public function create(Uuid $groupId, Request $request, Security $security): AbstractApiResponse
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

    #[Route('/api/group/post/delete/{postId}', name: 'api.group.post.delete', methods: ['DELETE'])]
    public function delete(Uuid $postId, Security $security): AbstractApiResponse
    {
        $userId = $this->getUserFromSecurity($security)->getId();

        if (!$this->postService->deletePost($userId, $postId)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
    }

    #[Route('/api/group/{groupId}/posts', name: 'api.group.posts', methods: ['GET'])]
    public function getPosts(Uuid $groupId, Security $security): AbstractApiResponse
    {
        $userId = $this->getUserFromSecurity($security)->getId();

        $posts = $this->postService->getPosts($userId, $groupId);
        if (empty($posts)) {
            return new FailureResponse();
        }

        return new PostResponse($posts);
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
