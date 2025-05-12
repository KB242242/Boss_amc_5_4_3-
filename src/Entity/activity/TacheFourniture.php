<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class TacheFourniture
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
    //-----3--refBesoin---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refBesoin = null;
    
	public function getRefBesoin(): ?int
    {
        return $this->refBesoin;
    }
    public function setRefBesoin(int $refBesoin): static
    {
        $this->refBesoin = $refBesoin;
        return $this;
    }
   //-----4--designation---string--------
    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;
        return $this;
    }
 	//-----5--qtDemande---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $qtDemande = null;

	public function getQtDemande(): ?float
	{
		return $this->qtDemande;
	}

	public function setQtDemande(?float $qtDemande): static
	{
		$this->qtDemande = $qtDemande;
		return $this;
	}
    //-----6--priorite---int----------
    #[ORM\Column( nullable: true)]
    private ?int $priorite = null;
    
	public function getPriorite(): ?int
    {
        return $this->priorite;
    }
    public function setPriorite(int $priorite): static
    {
        $this->priorite = $priorite;
        return $this;
    }

	//-------7----rem---------------------------------
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
	//-------8----valid---------------------------------
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
