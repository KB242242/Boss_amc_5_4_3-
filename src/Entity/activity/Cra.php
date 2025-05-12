<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class Cra
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
    //-----3--refUser---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refUser = null;
    
	public function getRefUser(): ?int
    {
        return $this->refUser;
    }
    public function setRefUser(int $refUser): static
    {
        $this->refUser = $refUser;
        return $this;
    }
   //-----4--date---string--------
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
    //-----5--refChefEquipe---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refChefEquipe = null;
    
	public function getRefChefEquipe(): ?int
    {
        return $this->refChefEquipe;
    }
    public function setRefChefEquipe(int $refChefEquipe): static
    {
        $this->refChefEquipe = $refChefEquipe;
        return $this;
    }
	//-------6--equipe---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $equipe = null;
	 public function getEquipe_(): ?string
    {
        return $this->equipe;
    }

    public function setEquipe_(string $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }
  	//-----7--tempsMob---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $tempsMob = null;

	public function getTempsMob(): ?float
	{
		return $this->tempsMob;
	}

	public function setTempsMob(?float $tempsMob): static
	{
		$this->tempsMob = $tempsMob;
		return $this;
	}
  	//-----8--tempsExec---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $tempsExec = null;

	public function getTempsExec(): ?float
	{
		return $this->tempsExec;
	}

	public function setTempsExec(?float $tempsExec): static
	{
		$this->tempsExec = $tempsExec;
		return $this;
	}
 	//-----9--tempsExecRest---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $tempsExecRest = null;

	public function getTempsExecRest(): ?float
	{
		return $this->tempsExecRest;
	}

	public function setTempsExecRest(?float $tempsExecRest): static
	{
		$this->tempsExecRest = $tempsExecRest;
		return $this;
	}
	//-------10--incidentAnomalie---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $incidentAnomalie = null;
	 public function getIncidentAnomalie(): ?string
    {
        return $this->incidentAnomalie;
    }

    public function setIncidentAnomalie(string $incidentAnomalie): static
    {
        $this->incidentAnomalie = $incidentAnomalie;

        return $this;
    }
   //-----11--mea_client---int----------
    #[ORM\Column( nullable: true)]
    private ?int $mea_client = null;
    
	public function getMea_client(): ?int
    {
        return $this->mea_client;
    }
    public function setMea_client(int $mea_client): static
    {
        $this->mea_client = $mea_client;
        return $this;
    }
    //-----12--mea_fourn---int----------
    #[ORM\Column( nullable: true)]
    private ?int $mea_fourn = null;
    
	public function getMea_fourn(): ?int
    {
        return $this->mea_fourn;
    }
    public function setMea_fourn(int $mea_fourn): static
    {
        $this->mea_fourn = $mea_fourn;
        return $this;
    }
    //-----13--mea_ress---int----------
    #[ORM\Column( nullable: true)]
    private ?int $mea_ress = null;
    
	public function getMea_ress(): ?int
    {
        return $this->mea_ress;
    }
    public function setMea_ress(int $mea_ress): static
    {
        $this->mea_ress = $mea_ress;
        return $this;
    }
    //-----14--mea_CRQ---int----------
    #[ORM\Column( nullable: true)]
    private ?int $mea_CRQ = null;
    
	public function getMea_CRQ(): ?int
    {
        return $this->mea_CRQ;
    }
    public function setMea_CRQ(int $mea_CRQ): static
    {
        $this->mea_CRQ = $mea_CRQ;
        return $this;
    }

	//-------15----rem---------------------------------
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
	//-------16----valid---------------------------------
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
