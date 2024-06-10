<?php declare(strict_types=1);

namespace App\Core\Content\Response\Group;

use App\Core\Content\Response\AbstractApiResponse;
use App\Entity\UserGroup;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GroupInvitesSearchResponse extends AbstractApiResponse
{
    const RESPONSE_TYPE = 'group_invites_results';

    /**
     * @param UserGroup[] $invites
     */
    public function __construct(array $invites)
    {
        parent::__construct();
        $this->object['invites'] = $invites;
    }

    /**
     * @return UserGroup[]
     */
    public function getInvites(): array
    {
        return $this->object['invites'];
    }

    public function formatResponse(): Response
    {
        return new JsonResponse([
            'data' => \array_map(
                static fn (UserGroup $userGroup) => [
                    'id' => $userGroup->getId(),
                    'group' => [
                        'id' => $userGroup->getGroupId(),
                        'name' => $userGroup->getGroup()->getGroupId(),
                        'createdAt' => $userGroup->getGroup()->getCreatedAt()->format(\DATE_RFC3339),
                    ],
                    'createdAt' => $userGroup->getCreatedAt()->format(\DATE_RFC3339),
                ],
                $this->getInvites()
            ),
            'type' => self::RESPONSE_TYPE
        ]);
    }
}