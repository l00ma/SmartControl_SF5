<?php

namespace App\Entity;

use App\Repository\SecurityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecurityRepository::class)]
class Security
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $camera = null;

    #[ORM\Column(length: 80)]
    private ?string $filename = null;

    #[ORM\Column(nullable: true)]
    private ?int $frame = null;

    #[ORM\Column(nullable: true)]
    private ?int $file_type = null;

    //#[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $time_stamp = null;
    //ou juste private $time_stamp (voir entity property de masuperagence)

    //#[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $even_time_stamp = null;
    //ou juste private $time_stamp (voir entity property de masuperagence)

    private ?string $relativeFilename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCamera(): ?int
    {
        return $this->camera;
    }

    public function setCamera(?int $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFrame(): ?int
    {
        return $this->frame;
    }

    public function setFrame(?int $frame): self
    {
        $this->frame = $frame;

        return $this;
    }

    public function getFileType(): ?int
    {
        return $this->file_type;
    }

    public function setFileType(?int $file_type): self
    {
        $this->file_type = $file_type;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->time_stamp;
    }

    public function setTimeStamp(\DateTimeInterface $time_stamp): self
    {
        $this->time_stamp = $time_stamp;

        return $this;
    }

    public function getEvenTimeStamp(): ?\DateTimeInterface
    {
        return $this->even_time_stamp;
    }

    public function setEvenTimeStamp(\DateTimeInterface $even_time_stamp): self
    {
        $this->even_time_stamp = $even_time_stamp;

        return $this;
    }

    public function getRelativeFilename(): ?string
    {
        return $this->relativeFilename;
    }

    public function setRelativeFilename(string $relativeFilename ): self
    {
        $this->relativeFilename = $relativeFilename;

        return $this;
    }
}
