<?php declare(strict_types=1);

namespace App\Core\Service\Post;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostService implements PostServiceInterface
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function createPost(User $user, Group $group, string $content): bool
    {
        $data = [
            'groupId' => $group->getGroupId(),
            'createdById' => $user->getId(),
            'content' => $content,
        ];

        return $this->sendCreatePost($data);
    }

    /**
     * @param mixed $data
     */
    private function sendCreatePost(array $data): bool
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_POST,
                $this->baseUrl . '/post/create',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => \json_encode($data),
                ]
            );

            return $response->getStatusCode() === Response::HTTP_CREATED;
        } catch (TransportExceptionInterface) {
            return false;
        }
    }
}