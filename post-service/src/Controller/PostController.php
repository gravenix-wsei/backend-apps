<?php

namespace PostService\Controller;

use PostService\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PostController extends AbstractController
{
    public function __construct(
        private readonly PostServiceInterface $postService
    ) {
    }

    #[Route('/post/create', name: 'post-service.post.create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $this->validateCreateRequest($request);
        if (!$this->postService->createPost(
            Uuid::fromString($request->get('createdById')),
            Uuid::fromString($request->get('groupId')),
            $request->get('content')
        )) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        return new Response(status: Response::HTTP_CREATED);
    }

    #[Route('/post/delete/{postId}', name: 'post-service.post.delete', methods: ['DELETE'])]
    public function delete(Request $request, Uuid $postId): Response
    {
        $userId = $request->get('userId');
        if (empty($userId) || !Uuid::isValid($userId)) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if (!$this->postService->deletePost(Uuid::fromString($userId), Uuid::fromString($postId))) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        return new Response(status: Response::HTTP_OK);
    }

    #[Route('/post/group/{groupId}', name: 'post-service.post.get', methods: ['GET'])]
    public function getPosts(Uuid $groupId): Response
    {
        $posts = $this->postService->getPostsFromGroup($groupId);

        return new JsonResponse($posts);
    }

    private function validateCreateRequest(Request $request): void
    {
        $groupId = $request->get('groupId');
        $createdById = $request->get('createdById');
        $content = $request->get('content');

        if (!$groupId || !Uuid::isValid($groupId)) {
            throw new BadRequestHttpException('Missing or invalid groupId');
        }
        if (!$createdById || !Uuid::isValid($createdById)) {
            throw new BadRequestHttpException('Missing or invalid createdById');
        }
        if (empty($content)) {
            throw new BadRequestHttpException('Missing content');
        }
    }
}
