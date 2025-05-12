<?php

namespace App\Entity\administratif;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class AnnuaireParticulier
{
      //-------1--id---int------------------ 
   #[ORM\Id]
   #[ORM\Column(unique:true)]
    private ?int $id = null;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
    //-----2--civilite---int----------
    #[ORM\Column( nullable: true)]
    private ?int $civilite = null;
    
	public function getCivilite(): ?int
    {
        return $this->civilite;
    }
    public function setCivilite(int $civilite): static
    {
        $this->civilite = $civilite;
        return $this;
    }
    //-----3--nom---string--------
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }
    //-----4--prenomUsuel---string--------
    #[ORM\Column(length: 255)]
    private ?string $prenomUsuel = null;

    public function getPrenomUsuel(): ?string
    {
        return $this->prenomUsuel;
    }

    public function setPrenomUsuel(?string $prenomUsuel): static
    {
        $this->prenomUsuel = $prenomUsuel;
        return $this;
    }
    //-----5--prenoms---string--------
    #[ORM\Column(length: 255)]
    private ?string $prenoms = null;

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;
        return $this;
    }

	//-------6----rem---------------------------------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rem = null;
	 public function getRem(): ?string
    {
        return $this->rem;
    }

    public function setRem(string $rem): static
    {
        $this->rem = $rem;

        return $this;
    }
	//-------7----valid---------------------------------
    #[ORM\Column]
    private ?bool $valid = null;
   public function getValid(): ?int
    {
        return $this->valid;
    }

    public function setValid(?int $valid): static
    {
        $this->valid = $valid;

        return $this;
    }
}
