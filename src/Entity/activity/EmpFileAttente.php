<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class EmpFileAttente
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
    //-----2--refFileAttente---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refFileAttente = null;
    
	public function getRefFileAttente(): ?int
    {
        return $this->refFileAttente;
    }
    public function setRefFileAttente(int $refFileAttente): static
    {
        $this->refFileAttente = $refFileAttente;
        return $this;
    }
    //-----3--refEmp---int----------
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
     //-----4--fonction---string--------
    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;
        return $this;
    }

   
	//-------5----rem---------------------------------
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
	//-------6----valid---------------------------------
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
