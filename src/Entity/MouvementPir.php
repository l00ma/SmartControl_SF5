<?php

namespace App\Entity;

use App\Repository\MouvementPirRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MouvementPirRepository::class)]
class MouvementPir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'mouvementPir', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Members $owner = null;

    #[ORM\Column]
    private ?int $graph_rafraich = null;

    #[ORM\Column(nullable: true)]
    private ?int $enreg = null;

    #[ORM\Column(nullable: true)]
    private ?int $enreg_detect = null;

    #[ORM\Column(nullable: true)]
    private ?int $alert = null;

    #[ORM\Column(nullable: true)]
    private ?int $alert_detect = null;

    #[ORM\Column(length: 8)]
    private ?string $espace_total = null;

    #[ORM\Column(length: 8)]
    private ?string $espace_dispo = null;

    #[ORM\Column(length: 5)]
    private ?string $taux_utilisation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGraphRafraich(): ?int
    {
        return $this->graph_rafraich;
    }

    public function setGraphRafraich(int $graph_rafraich): self
    {
        $this->graph_rafraich = $graph_rafraich;

        return $this;
    }

    public function getEnreg(): ?int
    {
        return $this->enreg;
    }

    public function setEnreg(?int $enreg): self
    {
        $this->enreg = $enreg;

        return $this;
    }

    public function getEnregDetect(): ?int
    {
        return $this->enreg_detect;
    }

    public function setEnregDetect(?int $enreg_detect): self
    {
        $this->enreg_detect = $enreg_detect;

        return $this;
    }

    public function getAlert(): ?int
    {
        return $this->alert;
    }

    public function setAlert(?int $alert): self
    {
        $this->alert = $alert;

        return $this;
    }

    public function getAlertDetect(): ?int
    {
        return $this->alert_detect;
    }

    public function setAlertDetect(?int $alert_detect): self
    {
        $this->alert_detect = $alert_detect;

        return $this;
    }

    public function getEspaceTotal(): ?string
    {
        return $this->espace_total;
    }

    public function setEspaceTotal(string $espace_total): self
    {
        $this->espace_total = $espace_total;

        return $this;
    }

    public function getEspaceDispo(): ?string
    {
        return $this->espace_dispo;
    }

    public function setEspaceDispo(string $espace_dispo): self
    {
        $this->espace_dispo = $espace_dispo;

        return $this;
    }

    public function getTauxUtilisation(): ?string
    {
        return $this->taux_utilisation;
    }

    public function setTauxUtilisation(string $taux_utilisation): self
    {
        $this->taux_utilisation = $taux_utilisation;

        return $this;
    }
}
