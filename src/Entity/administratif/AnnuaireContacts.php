<?php

namespace App\Entity\administratif;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class AnnuaireContacts
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
    //-----2--refParticulier---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refParticulier = null;
    
	public function getRefParticulier(): ?int
    {
        return $this->refParticulier;
    }
    public function setRefParticulier(int $refParticulier): static
    {
        $this->refParticulier = $refParticulier;
        return $this;
    }
    //-----3--typeContact---int----------
    #[ORM\Column( nullable: true)]
    private ?int $typeContact = null;
    
	public function getTypeContact(): ?int
    {
        return $this->typeContact;
    }
    public function setTypeContact(int $typeContact): static
    {
        $this->typeContact = $typeContact;
        return $this;
    }
   //-----4--contact---string--------
    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;
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
