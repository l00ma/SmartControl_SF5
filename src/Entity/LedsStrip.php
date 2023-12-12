<?php

namespace App\Entity;

use App\Repository\LedsStripRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LedsStripRepository::class)]
class LedsStrip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $item = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $etat = null;

    #[ORM\Column(length: 11)]
    private ?string $rgb = null;

    #[ORM\Column(length: 5)]
    private ?string $h_on = null;

    #[ORM\Column(length: 5)]
    private ?string $h_off = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $timer = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $email = null;

    #[ORM\Column]
    private ?int $effet = null;

    #[ORM\OneToOne(targetEntity: Members::class, inversedBy: 'ledsStrip', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Members $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRgb(): ?string
    {
        return $this->rgb;
    }

    public function setRgb(string $rgb): self
    {
        $this->rgb = $rgb;

        return $this;
    }

    public function getHOn(): ?string
    {
        return $this->h_on;
    }

    public function setHOn(string $h_on): self
    {
        $this->h_on = $h_on;

        return $this;
    }

    public function getHOff(): ?string
    {
        return $this->h_off;
    }

    public function setHOff(string $h_off): self
    {
        $this->h_off = $h_off;

        return $this;
    }

    public function getTimer(): ?bool
    {
        return $this->timer;
    }

    public function setTimer(bool $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    public function getEmail(): ?bool
    {
        return $this->email;
    }

    public function setEmail(bool $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEffet(): ?int
    {
        return $this->effet;
    }

    public function setEffet(int $effet): self
    {
        $this->effet = $effet;

        return $this;
    }

    public function getOwner(): ?Members
    {
        return $this->owner;
    }

    public function setOwner(Members $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
