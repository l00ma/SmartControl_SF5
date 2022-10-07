<?php

namespace App\Entity;

use App\Repository\MembersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MembersRepository::class)]
class Members implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToOne(targetEntity: MouvementPir::class, mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?MouvementPir $mouvementPir = null;

    #[ORM\OneToOne(targetEntity: LedsStrip::class, mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?LedsStrip $ledsStrip = null;

    #[ORM\OneToOne(targetEntity: Meteo::class, mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Meteo $meteo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMouvementPir(): ?MouvementPir
    {
        return $this->mouvementPir;
    }

    public function setMouvementPir(MouvementPir $mouvementPir): self
    {
        // set the owning side of the relation if necessary
        if ($mouvementPir->getOwner() !== $this) {
            $mouvementPir->setOwner($this);
        }

        $this->mouvementPir = $mouvementPir;

        return $this;
    }

    public function getLedsStrip(): ?LedsStrip
    {
        return $this->ledsStrip;
    }

    public function setLedsStrip(LedsStrip $ledsStrip): self
    {
        // set the owning side of the relation if necessary
        if ($ledsStrip->getOwner() !== $this) {
            $ledsStrip->setOwner($this);
        }

        $this->ledsStrip = $ledsStrip;

        return $this;
    }

    public function getMeteo(): ?Meteo
    {
        return $this->meteo;
    }

    public function setMeteo(Meteo $meteo): self
    {
        // set the owning side of the relation if necessary
        if ($meteo->getOwner() !== $this) {
            $meteo->setOwner($this);
        }

        $this->meteo = $meteo;

        return $this;
    }
}
