<?php


namespace App\Entity\personnel;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class Mpo
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
    //-----2--refEmp---string--------
    #[ORM\Column(length: 255)]
    private ?string $refEmp = null;

    public function getRefEmp(): ?string
    {
        return $this->refEmp;
    }

    public function setRefEmp(?string $refEmp): static
    {
        $this->refEmp = $refEmp;
        return $this;
    }

    //-----3--dateDebut---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateDebut = null;

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?string $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }
    //-----4--referent---int----------
    #[ORM\Column( nullable: true)]
    private ?int $referent = null;
    
	public function getReferent(): ?int
    {
        return $this->referent;
    }
    public function setReferent(int $referent): static
    {
        $this->referent = $referent;
        return $this;
    }
	//-------5--objectif---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objectif = null;
	 public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }
    //-----6--dateValidation---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateValidation = null;

    public function getDateValidation(): ?string
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?string $dateValidation): static
    {
        $this->dateValidation = $dateValidation;
        return $this;
    }
    //-----7--status---int----------
    #[ORM\Column( nullable: true)]
    private ?int $status = null;
    
	public function getStatus(): ?int
    {
        return $this->status;
    }
    public function setStatus(int $status): static
    {
        $this->status = $status;
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
