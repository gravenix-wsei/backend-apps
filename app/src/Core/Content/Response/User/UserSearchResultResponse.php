<?php declare(strict_types=1);

namespace App\Core\Content\Response\User;

use App\Core\Content\Response\AbstractApiResponse;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserSearchResultResponse extends AbstractApiResponse
{
    const RESPONSE_TYPE = 'user_search_results';

    /**
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        parent::__construct();
        $this->object['users'] = $users;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->object['users'];
    }

    public function formatResponse(): Response
    {
        return new JsonResponse([
            'data' => \array_map(
                static fn (User $user) => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'username' => $user->getUsername(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'createdAt' => $user->getCreatedAt()->format(\DATE_RFC3339),
                ],
                $this->getUsers()
            ),
            'type' => self::RESPONSE_TYPE
        ]);
    }
}