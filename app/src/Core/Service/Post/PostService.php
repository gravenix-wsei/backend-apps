<?php declare(strict_types=1);

namespace App\Core\Service\Post;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
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

    public function deletePost(Uuid $userId, Uuid $postId): bool
    {
        return $this->sendDeletePost($userId, $postId);
    }

    public function getPosts(Uuid $userId, Uuid $groupId): array
    {
        return $this->sendGetPostsForGroup($userId, $groupId);
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

    private function sendDeletePost(Uuid $userId, Uuid $postId): bool
    {
        try {
            return $this->httpClient->request(
                Request::METHOD_DELETE,
                $this->baseUrl . '/post/delete/' . $postId->toRfc4122(),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => \json_encode(['userId' => $userId->toRfc4122()]),
                ]
            )->getStatusCode() === Response::HTTP_OK;
        } catch (TransportExceptionInterface) {
            return false;
        }
    }

    private function sendGetPostsForGroup(Uuid $userId, Uuid $groupId): array
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                $this->baseUrl . '/post/group/' . $groupId->toRfc4122() . '/' . $userId->toRfc4122(),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => \json_encode(['userId' => $userId->toRfc4122()]),
                ]
            );
            if ($response->getStatusCode() !== Response::HTTP_OK) {
                return [];
            }

            return \json_decode($response->getContent(), true, flags: \JSON_THROW_ON_ERROR) ?: [];
        } catch (TransportExceptionInterface) {
            return [];
        }
    }
}