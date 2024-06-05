<?php

namespace App\Entity;

use App\Repository\UserFriendRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserFriendRepository::class)]
class UserFriend
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\OneToOne(targetEntity: User::class)]
    private ?Uuid $user_id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn('user_id', 'user_id')]
    private ?User $user = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn('friend_id', 'user_id')]
    private ?User $friend = null;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\OneToOne(targetEntity: User::class)]
    private ?Uuid $friend_id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private bool $accepted = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getUserId(): ?Uuid
    {
        return $this->user_id;
    }

    public function setUserId(Uuid $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFriendId(): ?Uuid
    {
        return $this->friend_id;
    }

    public function setFriendId(Uuid $friend_id): static
    {
        $this->friend_id = $friend_id;

        return $this;
    }

    public function getAccepted(): bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): static
    {
        $this->accepted = $accepted;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFriend(): User
    {
        return $this->friend;
    }
}
