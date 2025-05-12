<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class Crq
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
    //-----2--refTache---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refTache = null;
    
	public function getRefTache(): ?int
    {
        return $this->refTache;
    }
    public function setRefTache(int $refTache): static
    {
        $this->refTache = $refTache;
        return $this;
    }
	//-------3--observations---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observations = null;
	 public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }
   //-----4--refTacheCorrective---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refTacheCorrective = null;
    
	public function getRefTacheCorrective(): ?int
    {
        return $this->refTacheCorrective;
    }
    public function setRefTacheCorrective(int $refTacheCorrective): static
    {
        $this->refTacheCorrective = $refTacheCorrective;
        return $this;
    }
   //-----5--crqValid---int----------
    #[ORM\Column( nullable: true)]
    private ?int $crqValid = null;
    
	public function getCrqValid(): ?int
    {
        return $this->crqValid;
    }
    public function setCrqValid(int $crqValid): static
    {
        $this->crqValid = $crqValid;
        return $this;
    }
    //-----6--date---string--------
    #[ORM\Column(length: 255)]
    private ?string $date = null;

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;
        return $this;
    }
   //-----7--user---int----------
    #[ORM\Column( nullable: true)]
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
	//-------8----rem---------------------------------
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
	//-------9----valid---------------------------------
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
