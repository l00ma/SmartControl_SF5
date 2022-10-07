<?php

namespace App\Entity;

use App\Repository\MeteoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeteoRepository::class)]
class Meteo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $temp_ext = null;

    #[ORM\Column(length: 4)]
    private ?string $pression = null;

    #[ORM\Column(length: 5)]
    private ?string $vitesse_vent = null;

    #[ORM\Column(length: 2)]
    private ?string $direction_vent = null;

    #[ORM\Column(length: 100)]
    private ?string $location = null;

    #[ORM\Column(length: 3)]
    private ?string $humidite = null;

    #[ORM\Column(length: 40)]
    private ?string $weather = null;

    #[ORM\Column(length: 20)]
    private ?string $icon_id = null;

    #[ORM\Column(length: 5)]
    private ?string $leve_soleil = null;

    #[ORM\Column(length: 5)]
    private ?string $couche_soleil = null;

    #[ORM\Column(length: 4)]
    private ?string $temp_f1 = null;

    #[ORM\Column(length: 4)]
    private ?string $temp_f2 = null;

    #[ORM\Column(length: 4)]
    private ?string $temp_f3 = null;

    #[ORM\Column(length: 5)]
    private ?string $time_f1 = null;

    #[ORM\Column(length: 5)]
    private ?string $time_f2 = null;

    #[ORM\Column(length: 5)]
    private ?string $time_f3 = null;

    #[ORM\Column(length: 40)]
    private ?string $weather_f1 = null;

    #[ORM\Column(length: 40)]
    private ?string $weather_f2 = null;

    #[ORM\Column(length: 40)]
    private ?string $weather_f3 = null;

    #[ORM\Column(length: 20)]
    private ?string $icon_f1 = null;

    #[ORM\Column(length: 20)]
    private ?string $icon_f2 = null;

    #[ORM\Column(length: 20)]
    private ?string $icon_f3 = null;

    #[ORM\OneToOne(targetEntity: Members::class, inversedBy: 'meteo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Members $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTempExt(): ?string
    {
        return $this->temp_ext;
    }

    public function setTempExt(string $temp_ext): self
    {
        $this->temp_ext = $temp_ext;

        return $this;
    }

    public function getPression(): ?string
    {
        return $this->pression;
    }

    public function setPression(string $pression): self
    {
        $this->pression = $pression;

        return $this;
    }

    public function getVitesseVent(): ?string
    {
        return $this->vitesse_vent;
    }

    public function setVitesseVent(string $vitesse_vent): self
    {
        $this->vitesse_vent = $vitesse_vent;

        return $this;
    }

    public function getDirectionVent(): ?string
    {
        return $this->direction_vent;
    }

    public function setDirectionVent(string $direction_vent): self
    {
        $this->direction_vent = $direction_vent;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getHumidite(): ?string
    {
        return $this->humidite;
    }

    public function setHumidite(string $humidite): self
    {
        $this->humidite = $humidite;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getIconId(): ?string
    {
        return $this->icon_id;
    }

    public function setIconId(string $icon_id): self
    {
        $this->icon_id = $icon_id;

        return $this;
    }

    public function getLeveSoleil(): ?string
    {
        return $this->leve_soleil;
    }

    public function setLeveSoleil(string $leve_soleil): self
    {
        $this->leve_soleil = $leve_soleil;

        return $this;
    }

    public function getCoucheSoleil(): ?string
    {
        return $this->couche_soleil;
    }

    public function setCoucheSoleil(string $couche_soleil): self
    {
        $this->couche_soleil = $couche_soleil;

        return $this;
    }

    public function getTempF1(): ?string
    {
        return $this->temp_f1;
    }

    public function setTempF1(string $temp_f1): self
    {
        $this->temp_f1 = $temp_f1;

        return $this;
    }

    public function getTempF2(): ?string
    {
        return $this->temp_f2;
    }

    public function setTempF2(string $temp_f2): self
    {
        $this->temp_f2 = $temp_f2;

        return $this;
    }

    public function getTempF3(): ?string
    {
        return $this->temp_f3;
    }

    public function setTempF3(string $temp_f3): self
    {
        $this->temp_f3 = $temp_f3;

        return $this;
    }

    public function getTimeF1(): ?string
    {
        return $this->time_f1;
    }

    public function setTimeF1(string $time_f1): self
    {
        $this->time_f1 = $time_f1;

        return $this;
    }

    public function getTimeF2(): ?string
    {
        return $this->time_f2;
    }

    public function setTimeF2(string $time_f2): self
    {
        $this->time_f2 = $time_f2;

        return $this;
    }

    public function getTimeF3(): ?string
    {
        return $this->time_f3;
    }

    public function setTimeF3(string $time_f3): self
    {
        $this->time_f3 = $time_f3;

        return $this;
    }

    public function getWeatherF1(): ?string
    {
        return $this->weather_f1;
    }

    public function setWeatherF1(string $weather_f1): self
    {
        $this->weather_f1 = $weather_f1;

        return $this;
    }

    public function getWeatherF2(): ?string
    {
        return $this->weather_f2;
    }

    public function setWeatherF2(string $weather_f2): self
    {
        $this->weather_f2 = $weather_f2;

        return $this;
    }

    public function getWeatherF3(): ?string
    {
        return $this->weather_f3;
    }

    public function setWeatherF3(string $weather_f3): self
    {
        $this->weather_f3 = $weather_f3;

        return $this;
    }

    public function getIconF1(): ?string
    {
        return $this->icon_f1;
    }

    public function setIconF1(string $icon_f1): self
    {
        $this->icon_f1 = $icon_f1;

        return $this;
    }

    public function getIconF2(): ?string
    {
        return $this->icon_f2;
    }

    public function setIconF2(string $icon_f2): self
    {
        $this->icon_f2 = $icon_f2;

        return $this;
    }

    public function getIconF3(): ?string
    {
        return $this->icon_f3;
    }

    public function setIconF3(string $icon_f3): self
    {
        $this->icon_f3 = $icon_f3;

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
