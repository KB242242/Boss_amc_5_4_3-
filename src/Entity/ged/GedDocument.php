<?php

namespace App\Entity\ged;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class GedDocument
{
   //-------1-id------------------- 
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
    #[ORM\Column(length: 255, nullable: true)]
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
	//-------3--userRes---int----------------
    #[ORM\Column( nullable: true)]
    private ?int $userRes = null;

    public function getUserRes(): ?int
    {
        return $this->userRes;
    }

    public function setUserRes(int $userRes): static
    {
        $this->userRes = $userRes;
        return $this;
    }
    //--------4-dateRes---- -----------------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateRes = null;

    public function getDateRes(): ?string
    {
        return $this->dateRes;
    }
    public function setDateRes(?string $dateRes): static
    {
        $this->dateRes = $dateRes;
        return $this;
    }
    //--------5-userVal---- -------------------
    #[ORM\Column( nullable: true)]
    private ?int $userVal = null;

    public function getUserVal(): ?int
    {
        return $this->userVal;
    }

    public function setUserVal(int $userVal): static
    {
        $this->userVal = $userVal;
        return $this;
    }

	//--------6-dateVal---- -----------------
     #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateVal = null;

    public function getDateVal(): ?string
    {
        return $this->dateVal;
    }
    public function setDateVal(?string $dateVal): static
    {
        $this->dateVal = $dateVal;
        return $this;
    }
    //--------7-titre---string--------
    #[ORM\Column(length: 255, nullable: true)]
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
//--------8-extension---string--------
    #[ORM\Column(length: 255, nullable: true)]
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
        //-------9-typeDossier---string--------
        #[ORM\Column(length: 255, nullable: true)]
        private ?string $typeDossier = null;
    
        public function getTypeDossier(): ?string
        {
            return $this->typeDossier;
        }
    
        public function setTypeDossier(string $typeDossier): static
        {
            $this->typeDossier = $typeDossier;
            return $this;
        }
    //-------9-dossier---string--------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dossier = null;

    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(string $dossier): static
    {
        $this->dossier = $dossier;
        return $this;
    }
    
    //--------10-version---string--------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $version = null;

    public function getVersion(): ?string
    {
        return $this->version;
    }
    public function setVersion(?string $version): static
    {
        $this->version = $version;
        return $this;
    }
    //--------11-droits---TEXT----------------
      #[ORM\Column(type: Types::TEXT, nullable: true)]
      private ?string $droits = null;
  
       public function getDroits(): ?string
      {
          return $this->droits;
      }
  
      public function setDroits(string $droits): static
      {
          $this->droits = $droits;
  
          return $this;
      }
     //--------12-validite---string----------------
     #[ORM\Column(length: 255, nullable: true)]
     private ?string $validite = null;
     // 0-pending; 1-enregistre dans la base; 2-refusé
     public function getValidite(): ?int
     {
         return $this->validite;
     }
 
     public function setValidite(int $validite): static
     {
         $this->validite = $validite;
         return $this;
     }

      //--------13-classement---string----------------
      #[ORM\Column(length: 255, nullable: true)]
      private ?string $classement = null;
      // 0-pending; 1-enregistre dans la base; 2-refusé
      public function getClassement(): ?int
      {
          return $this->classement;
      }
  
      public function setClassement(int $classement): static
      {
          $this->classement = $classement;
          return $this;
      }
      //--------14-motsCles---TEXT----------------
      #[ORM\Column(type: Types::TEXT, nullable: true)]
      private ?string $motsCles = null;
  
       public function getMotsCles(): ?string
      {
          return $this->motsCles;
      }
  
      public function setMotsCles(string $motsCles): static
      {
          $this->motsCles = $motsCles;
  
          return $this;
      }
	//-------------15-rem---TEXT------------------
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
	//-------------16-valid---------------------------------
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