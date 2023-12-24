<?php

namespace App\Entity;

use App\Repository\ManageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManageRepository::class)]
class Manage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $motion = null;

    #[ORM\Column]
    private ?bool $reboot = null;

    #[ORM\Column]
    private ?bool $halt = null;

    #[ORM\OneToOne(inversedBy: 'manage', cascade: ['persist', 'remove'])]
    private ?Members $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMotion(): ?bool
    {
        return $this->motion;
    }

    public function setMotion(bool $motion): static
    {
        $this->motion = $motion;

        return $this;
    }

    public function isReboot(): ?bool
    {
        return $this->reboot;
    }

    public function setReboot(bool $reboot): static
    {
        $this->reboot = $reboot;

        return $this;
    }

    public function isHalt(): ?bool
    {
        return $this->halt;
    }

    public function setHalt(bool $halt): static
    {
        $this->halt = $halt;

        return $this;
    }

    public function getOwner(): ?Members
    {
        return $this->owner;
    }

    public function setOwner(?Members $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
