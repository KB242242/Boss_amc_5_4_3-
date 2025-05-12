<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class FileAttenteTache
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
   //-----3--refTache---int----------
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
    //-----4--ordreArrivee---int----------
    #[ORM\Column( nullable: true)]
    private ?int $ordreArrivee = null;
    
	public function getOrdreArrivee(): ?int
    {
        return $this->ordreArrivee;
    }
    public function setOrdreArrivee(int $ordreArrivee): static
    {
        $this->ordreArrivee = $ordreArrivee;
        return $this;
    }
    //-----5--ordreExecution---int----------
    #[ORM\Column( nullable: true)]
    private ?int $ordreExecution = null;
    
	public function getOrdreExecution(): ?int
    {
        return $this->ordreExecution;
    }
    public function setOrdreExecution(int $ordreExecution): static
    {
        $this->ordreExecution = $ordreExecution;
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
