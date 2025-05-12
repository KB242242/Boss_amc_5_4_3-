<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class FileAttenteStat
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
     //-----3--nbTacheAttente---int----------
    #[ORM\Column( nullable: true)]
    private ?int $nbTacheAttente = null;
    
	public function getNbTacheAttente(): ?int
    {
        return $this->nbTacheAttente;
    }
    public function setNbTacheAttente(int $nbTacheAttente): static
    {
        $this->nbTacheAttente = $nbTacheAttente;
        return $this;
    }
    //-----4--totalTempsAttente---int----------
    #[ORM\Column( nullable: true)]
    private ?int $totalTempsAttente = null;
    
	public function getTotalTempsAttente(): ?int
    {
        return $this->totalTempsAttente;
    }
    public function setTotalTempsAttente(int $totalTempsAttente): static
    {
        $this->totalTempsAttente = $totalTempsAttente;
        return $this;
    }
    //-----5--dateDeb---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateDeb = null;

    public function getDateDeb(): ?string
    {
        return $this->dateDeb;
    }

    public function setDateDeb(?string $dateDeb): static
    {
        $this->dateDeb = $dateDeb;
        return $this;
    }
    //-----6--dateFin---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateFin = null;

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

	//-------14----rem---------------------------------
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
	//-------15----valid---------------------------------
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
