<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserGroupRepository::class)]
#[ORM\UniqueConstraint(
    name: 'uniq_user_group',
    columns: ['user_id', 'group_id']
)]
class UserGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column('user_group_id', UuidType::NAME)]
    private ?Uuid $id = null;

    #[ORM\Column('group_id', UuidType::NAME)]
    private ?Uuid $groupId = null;

    #[ORM\Column('user_id', UuidType::NAME)]
    private ?Uuid $userId = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToOne(targetEntity: Group::class)]
    #[ORM\JoinColumn('group_id', 'group_id')]
    private ?Group $group = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn('user_id', 'user_id')]
    private ?User $user = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private bool $accepted = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getGroupId(): ?Uuid
    {
        return $this->groupId;
    }

    public function setGroupId(?Uuid $groupId): static
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getUserId(): ?Uuid
    {
        return $this->userId;
    }

    public function setUserId(?Uuid $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setIsAccepted(bool $accepted): static
    {
        $this->accepted = $accepted;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
