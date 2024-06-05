<?php declare(strict_types=1);

namespace App\Core\Content\Response\Group;

use App\Core\Content\Response\AbstractApiResponse;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupSearchResultResponse extends AbstractApiResponse
{
    const RESPONSE_TYPE = 'group_search_results';

    /**
     * @param Group[] $groups
     */
    public function __construct(array $groups)
    {
        parent::__construct();
        $this->object['groups'] = $groups;
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->object['groups'];
    }

    public function formatResponse(): Response
    {
        return new JsonResponse([
            'data' => \array_map(
                static fn (Group $group) => [
                    'id' => $group->getGroupId(),
                    'name' => $group->getName(),
                    'createdAt' => $group->getCreatedAt()->format(\DATE_RFC3339),
                ],
                $this->getGroups()
            ),
            'type' => self::RESPONSE_TYPE
        ]);
    }
}