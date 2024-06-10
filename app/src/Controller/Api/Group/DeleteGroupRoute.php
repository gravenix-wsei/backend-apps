<?php

namespace App\Controller\Api\Group;

use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class DeleteGroupRoute extends AbstractController
{
    public function __construct(
        private readonly GroupServiceInterface $groupService
    ) {
    }

    #[Route('/api/group/{groupId}', name: 'api.group.delete', methods: ['DELETE'])]
    public function delete(Uuid $groupId, Security $security): Response
    {
        $user =  $this->getUserFromSecurity($security);
        if (!$groupId) {
            throw new BadRequestHttpException('Missing "groupId"');
        }

        if (!$this->groupService->canDeleteGroup($groupId, $user->getId())) {
            throw new BadRequestHttpException('You dont have permissions to perform this action');
        }

        if (!$this->groupService->deleteGroup($groupId)) {
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
