<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class TachePreliminaire
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
    //-----3--refTachePrelim---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refTachePrelim = null;
    
	public function getRefTachePrelim(): ?int
    {
        return $this->refTachePrelim;
    }
    public function setRefTachePrelim(int $refTachePrelim): static
    {
        $this->refTachePrelim = $refTachePrelim;
        return $this;
    }
   //-----4--ordre---int----------
    #[ORM\Column( nullable: true)]
    private ?int $ordre = null;
    
	public function getOrdre(): ?int
    {
        return $this->ordre;
    }
    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;
        return $this;
    }
   //-----5--status---int----------
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
