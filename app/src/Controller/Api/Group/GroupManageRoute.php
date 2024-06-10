<?php declare(strict_types=1);

namespace App\Controller\Api\Group;

use App\Core\Content\Response\AbstractApiResponse;
use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\Group\GroupInvitesSearchResponse;
use App\Core\Content\Response\Group\GroupSearchResultResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class GroupManageRoute extends AbstractController
{
    public function __construct(
        private readonly GroupServiceInterface $groupService
    ) {
    }

    #[Route('/api/group/{groupId}/user/invite/{userId}', 'api.group.user.invite', methods: ['PUT'])]
    public function invite(Uuid $groupId, Uuid $userId, Security $security): AbstractApiResponse
    {
        $user = $this->getUserFromSecurity($security);
        $this->validateUserIsAdminOfGroup($user, $groupId);

        if (!$this->groupService->inviteUser($groupId, $userId)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
    }

    #[Route('/api/group/invites', 'api.group.invites', methods: ['GET'])]
    public function listInvites(Security $security): AbstractApiResponse
    {
        $user = $this->getUserFromSecurity($security);

        return new GroupInvitesSearchResponse($this->groupService->getInvitesForUser($user));
    }

    #[Route('/api/group/user/accept/{invitationId}', 'api.group.user.accept', methods: ['PUT'])]
    public function accept(Uuid $invitationId, Security $security): AbstractApiResponse
    {
        $user = $this->getUserFromSecurity($security);
        if (!$this->groupService->acceptInvitationAsUser($invitationId, $user)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
    }

    #[Route('/api/group/{groupId}/user/remove/{userId}', 'api.group.user.remove', methods: ['DELETE'])]
    public function removeUser(Uuid $groupId, Uuid $userId, Security $security): AbstractApiResponse
    {
        $user = $this->getUserFromSecurity($security);
        $this->validateUserIsAdminOfGroup($user, $groupId);

        if (!$this->groupService->removeUser($groupId, $userId)) {
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

    private function validateUserIsAdminOfGroup(User $user, Uuid $groupId): void
    {
        if (!$user->isAdminOfGroup($groupId)) {
            throw new AccessDeniedHttpException('Only group owner can perform this action');
        }
    }
}