<?php declare(strict_types=1);

namespace App\Core\Content\Response\Post;

use App\Core\Content\Response\AbstractApiResponse;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostResponse extends AbstractApiResponse
{
    const RESPONSE_TYPE = 'post';

    public function __construct(array $posts)
    {
        parent::__construct();
        $this->object['posts'] = $posts;
    }

    public function getPosts(): array
    {
        return $this->object['posts'];
    }

    public function formatResponse(): Response
    {
        return new JsonResponse([
            'data' => $this->getPosts(),
            'type' => self::RESPONSE_TYPE
        ]);
    }
}