<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_transporteur = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_transporteur = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Lcommande::class)]
    private Collection $lcommandes;

    #[ORM\Column]
    private ?bool $isPaie = null;

    public function __construct()
    {
        $this->lcommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getNomTransporteur(): ?string
    {
        return $this->nom_transporteur;
    }

    public function setNomTransporteur(?string $nom_transporteur): self
    {
        $this->nom_transporteur = $nom_transporteur;

        return $this;
    }

    public function getPrixTransporteur(): ?float
    {
        return $this->prix_transporteur;
    }

    public function setPrixTransporteur(?float $prix_transporteur): self
    {
        $this->prix_transporteur = $prix_transporteur;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Lcommande>
     */
    public function getLcommandes(): Collection
    {
        return $this->lcommandes;
    }

    public function addLcommande(Lcommande $lcommande): self
    {
        if (!$this->lcommandes->contains($lcommande)) {
            $this->lcommandes->add($lcommande);
            $lcommande->setCommande($this);
        }

        return $this;
    }

    public function removeLcommande(Lcommande $lcommande): self
    {
        if ($this->lcommandes->removeElement($lcommande)) {
            // set the owning side to null (unless already changed)
            if ($lcommande->getCommande() === $this) {
                $lcommande->setCommande(null);
            }
        }

        return $this;
    }

    public function isIsPaie(): ?bool
    {
        return $this->isPaie;
    }

    public function setIsPaie(bool $isPaie): self
    {
        $this->isPaie = $isPaie;

        return $this;
    }
}
