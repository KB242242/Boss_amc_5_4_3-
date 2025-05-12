<?php

namespace App\Entity\ged;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class GedReservation
{
     //---------id---int--refDoc---------------- 
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
    //--------2-gisement--string-------
    #[ORM\Column(length: 255)]
    private ?string $gisement = null;

    public function getGisement(): ?string
    {
        return $this->gisement;
    }

    public function setGisement(?string $gisement): static
    {
        $this->gisement = $gisement;
        return $this;
    }
	//-------3--user---int----------------
    #[ORM\Column]
    private ?int $user = null;

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): static
    {
        $this->user = $user;
        return $this;
    }
	//--------4-dateReserv---- -----------------
     #[ORM\Column(length: 255)]
    private ?string $datRes = null;

    public function getDatRes(): ?string
    {
        return $this->datRes;
    }
    public function setDatRes(?string $datRes): static
    {
        $this->datRes = $datRes;
        return $this;
    }
    //--------5-titre---string--------
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    public function getTitre(): ?string
    {
        return $this->titre;
    }
    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }
//--------6-extension---string--------
    #[ORM\Column(length: 255)]
    private ?string $extension = null;

    public function getExtension(): ?string
    {
        return $this->extension;
    }
    public function setExtension(?string $extension): static
    {
        $this->extension = $extension;
        return $this;
    }
    //--------7-refHebergement---int--------
    #[ORM\Column]
    private ?int $refHebergement = null;

    public function getRefHebergement(): ?int
    {
        return $this->refHebergement;
    }

    public function setRefHebergement(int $refHebergement): static
    {
        $this->refHebergement = $refHebergement;
        return $this;
    }
    //--------8-fichier---string--------
    #[ORM\Column(length: 255)]
    private ?string $fichier = null;

    public function getFichier(): ?string
    {
        return $this->fichier;
    }
    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;
        return $this;
    }
    //--------9-status---int----------------
    #[ORM\Column]
    private ?int $status = null;
	// 0-pending; 1-enregistre dans la base; 2-refusé
    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }
 //--------10-userValid---int----------------
    #[ORM\Column]
    private ?int $userValid = null;
	// 0-pending; 1-enregistre dans la base; 2-refusé
    public function getUserValid(): ?int
    {
        return $this->userValid;
    }

    public function setUserValid(int $userValid): static
    {
        $this->userValid = $userValid;
        return $this;
    }
	//-------------11-rem---------------------------------
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
	//-------------12-valid---------------------------------
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
