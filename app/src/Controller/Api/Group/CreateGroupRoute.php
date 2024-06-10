<?php declare(strict_types=1);

namespace App\Controller\Api\Group;

use App\Core\Content\Response\AbstractApiResponse;
use App\Core\Content\Response\FailureResponse;
use App\Core\Content\Response\SuccessResponse;
use App\Core\Service\Group\GroupServiceInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreateGroupRoute extends AbstractController
{
    public function __construct(
        private readonly GroupServiceInterface $groupService
    )
    {
    }

    #[Route('/api/group/create', methods: ['POST'])]
    public function __invoke(Request $request, Security $security): AbstractApiResponse
    {
        $name = $request->get('name');
        $user = $security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('/api/user/login', 'You must be logged in');
        }
        if (!$name) {
            throw new BadRequestHttpException('Missing "name" parameter');
        }
        if (!$user instanceof User) {
            throw new \RuntimeException('User is object is not valid');
        }

        if (!$this->groupService->createGroup($name, $user)) {
            return new FailureResponse();
        }

        return new SuccessResponse();
    }
}