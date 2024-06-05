<?php

namespace App\Controller\Api\User;

use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Content\Response\User\UserSearchResultResponse;
use App\Core\Service\User\UserService;
use App\Core\Service\User\UserServiceInterface;
use App\Entity\User;
use App\Entity\UserFriend;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class FriendsRoute extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService
    )
    {
    }

    #[Route('/api/user/friends', name: 'api.user.friends.get', methods: ['GET'])]
    public function getFriends(Request $request): Response
    {
        // TODO
        throw new \RuntimeException('Not implemented');
    }

    #[Route('/api/user/friends/invites', name: 'api.user.friends.get-invites', methods: ['GET'])]
    public function getFriendInvites(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user instanceof User) {
            throw new PreconditionFailedHttpException('Not valid user performing a request');
        }

        $results = $this->userService->listUserFriendRequests($user->getId());

        return new UserSearchResultResponse(
            \array_map(
                static fn (UserFriend $friendRequest) => $friendRequest->getFriend(),
                \array_filter($results, static fn($el) => $el instanceof UserFriend)
            )
        );
    }

    #[Route('/api/user/friends/invite/{userId}', name: 'api.user.friends.invite', methods: ['PUT'])]
    public function inviteFriend(Request $request, string $userId, Security $security): Response
    {
        $uuidInvited = Uuid::fromString($userId);
        $user = $security->getUser();
        if (!$user instanceof User) {
            throw new PreconditionFailedHttpException('Not valid user performing a request');
        }
        if (!$this->userService->inviteUser($user->getId(), $uuidInvited)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
    }

    #[Route('/api/user/friends/accept/{userId}', name: 'api.user.friends.accept', methods: ['PUT'])]
    public function acceptFriendRequest(Request $request, string $userId): Response
    {
        // TODO
        throw new \RuntimeException('Not implemented');
    }
}
