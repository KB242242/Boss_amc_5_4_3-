<?php

namespace App\Entity\personnel;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]

class competencesEmp
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
    //-----2--refEmp---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refEmp = null;
    
	public function getRefEmp(): ?int
    {
        return $this->refEmp;
    }
    public function setRefEmp(int $refEmp): static
    {
        $this->refEmp = $refEmp;
        return $this;
    }
    //-----3--reftacheStd---int----------
    #[ORM\Column( nullable: true)]
    private ?int $reftacheStd = null;
    
	public function getReftacheStd(): ?int
    {
        return $this->reftacheStd;
    }
    public function setReftacheStd(int $reftacheStd): static
    {
        $this->reftacheStd = $reftacheStd;
        return $this;
    }
    //-----4--dateHabilitation---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateHabilitation = null;

    public function getDateHabilitation(): ?string
    {
        return $this->dateHabilitation;
    }

    public function setDateHabilitation(?string $dateHabilitation): static
    {
        $this->dateHabilitation = $dateHabilitation;
        return $this;
    }
    //-----5--niveau---int----------
    #[ORM\Column( nullable: true)]
    private ?int $niveau = null;
    
	public function getNiveau(): ?int
    {
        return $this->niveau;
    }
    public function setNiveau(int $niveau): static
    {
        $this->niveau = $niveau;
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
