<?php

namespace App\Entity;

use App\Repository\MeteoMemoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeteoMemoryRepository::class)]
class MeteoMemory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_int_min = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_int_min_date = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_int_max = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_int_max_date = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_ext_min = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_ext_min_date = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_ext_max = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_ext_max_date = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_bas_min = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_bas_min_date = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $temp_bas_max = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $temp_bas_max_date = null;

    #[ORM\OneToOne(inversedBy: 'meteoMemory', cascade: ['persist', 'remove'])]
    private ?Members $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTempIntMin(): ?string
    {
        return $this->temp_int_min;
    }

    public function setTempIntMin(string $temp_int_min): self
    {
        $this->temp_int_min = $temp_int_min;

        return $this;
    }

    public function getTempIntMinDate(): ?\DateTimeInterface
    {
        return $this->temp_int_min_date;
    }

    public function setTempIntMinDate(\DateTimeInterface $temp_int_min_date): self
    {
        $this->temp_int_min_date = $temp_int_min_date;

        return $this;
    }

    public function getTempIntMax(): ?string
    {
        return $this->temp_int_max;
    }

    public function setTempIntMax(string $temp_int_max): self
    {
        $this->temp_int_max = $temp_int_max;

        return $this;
    }

    public function getTempIntMaxDate(): ?\DateTimeInterface
    {
        return $this->temp_int_max_date;
    }

    public function setTempIntMaxDate(\DateTimeInterface $temp_int_max_date): self
    {
        $this->temp_int_max_date = $temp_int_max_date;

        return $this;
    }

    public function getTempExtMin(): ?string
    {
        return $this->temp_ext_min;
    }

    public function setTempExtMin(string $temp_ext_min): self
    {
        $this->temp_ext_min = $temp_ext_min;

        return $this;
    }

    public function getTempExtMinDate(): ?\DateTimeInterface
    {
        return $this->temp_ext_min_date;
    }

    public function setTempExtMinDate(\DateTimeInterface $temp_ext_min_date): self
    {
        $this->temp_ext_min_date = $temp_ext_min_date;

        return $this;
    }

    public function getTempExtMax(): ?string
    {
        return $this->temp_ext_max;
    }

    public function setTempExtMax(string $temp_ext_max): self
    {
        $this->temp_ext_max = $temp_ext_max;

        return $this;
    }

    public function getTempExtMaxDate(): ?\DateTimeInterface
    {
        return $this->temp_ext_max_date;
    }

    public function setTempExtMaxDate(\DateTimeInterface $temp_ext_max_date): self
    {
        $this->temp_ext_max_date = $temp_ext_max_date;

        return $this;
    }

    public function getTempBasMin(): ?string
    {
        return $this->temp_bas_min;
    }

    public function setTempBasMin(string $temp_bas_min): self
    {
        $this->temp_bas_min = $temp_bas_min;

        return $this;
    }

    public function getTempBasMinDate(): ?\DateTimeInterface
    {
        return $this->temp_bas_min_date;
    }

    public function setTempBasMinDate(\DateTimeInterface $temp_bas_min_date): self
    {
        $this->temp_bas_min_date = $temp_bas_min_date;

        return $this;
    }

    public function getTempBasMax(): ?string
    {
        return $this->temp_bas_max;
    }

    public function setTempBasMax(string $temp_bas_max): self
    {
        $this->temp_bas_max = $temp_bas_max;

        return $this;
    }

    public function getTempBasMaxDate(): ?\DateTimeInterface
    {
        return $this->temp_bas_max_date;
    }

    public function setTempBasMaxDate(\DateTimeInterface $temp_bas_max_date): self
    {
        $this->temp_bas_max_date = $temp_bas_max_date;

        return $this;
    }

    public function getOwner(): ?Members
    {
        return $this->owner;
    }

    public function setOwner(?Members $owner): static
    {
        $this->owner= $owner;

        return $this;
    }
}
