<?php

namespace App\Entity\activity;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity()]
class Tache
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
    //-----2--reftacheActivity---string--------
    #[ORM\Column(length: 255)]
    private ?string $reftacheActivity = null;

    public function getReftacheActivity(): ?string
    {
        return $this->reftacheActivity;
    }

    public function setReftacheActivity(?string $reftacheActivity): static
    {
        $this->reftacheActivity = $reftacheActivity;
        return $this;
    }
    //-----3--modeleStdTache---int----------
    #[ORM\Column( nullable: true)]
    private ?int $modeleStdTache = null;
    
	public function getModeleStdTache(): ?int
    {
        return $this->modeleStdTache;
    }
    public function setModeleStdTache(int $modeleStdTache): static
    {
        $this->modeleStdTache = $modeleStdTache;
        return $this;
    }
    //----4--libelle---string--------
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;
        return $this;
    }
    //-----5--groupeTache---int----------
    #[ORM\Column( nullable: true)]
    private ?int $groupeTache = null;
    
	public function getGroupeTache(): ?int
    {
        return $this->groupeTache;
    }
    public function setGroupeTache(int $groupeTache): static
    {
        $this->groupeTache = $groupeTache;
        return $this;
    }
    //-----6--unitePeriode---int----------
    #[ORM\Column( nullable: true)]
    private ?int $unitePeriode = null;
    
	public function getUnitePeriode(): ?int
    {
        return $this->unitePeriode;
    }
    public function setUnitePeriode(int $unitePeriode): static
    {
        $this->unitePeriode = $unitePeriode;
        return $this;
    }
    //-----7--periode---int----------
    #[ORM\Column( nullable: true)]
    private ?int $periode = null;
    
	public function getPeriode(): ?int
    {
        return $this->periode;
    }
    public function setPeriode(int $periode): static
    {
        $this->periode = $periode;
        return $this;
    }
    //-----8--dateCreat---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateCreat = null;

    public function getDateCreat(): ?string
    {
        return $this->dateCreat;
    }

    public function setDateCreat(?string $dateCreat): static
    {
        $this->dateCreat = $dateCreat;
        return $this;
    }
    //-----9--dateVal---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateVal = null;

    public function getDateVal(): ?string
    {
        return $this->dateVal;
    }

    public function setDateVal(?string $dateVal): static
    {
        $this->dateVal = $dateVal;
        return $this;
    }
    //-----10--refImpClient---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refImpClient = null;
    
	public function getRefImpClient(): ?int
    {
        return $this->refImpClient;
    }
    public function setRefImpClient(int $refImpClient): static
    {
        $this->refImpClient = $refImpClient;
        return $this;
    }
    //-----11--refUrgClient---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refUrgClient = null;
    
	public function getRefUrgClient(): ?int
    {
        return $this->refUrgClient;
    }
    public function setRefUrgClient(int $refUrgClient): static
    {
        $this->refUrgClient = $refUrgClient;
        return $this;
    }
    //-----12--priorite---int----------
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
    //-----13--dateLimiteExecution---string--------
    #[ORM\Column(length: 255)]
    private ?string $dateLimiteExecution = null;

    public function getDateLimiteExecution(): ?string
    {
        return $this->dateLimiteExecution;
    }

    public function setDateLimiteExecution(?string $dateLimiteExecution): static
    {
        $this->dateLimiteExecution = $dateLimiteExecution;
        return $this;
    }
  	//-----14--delaisRealisationEstime---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $delaisRealisationEstime = null;

	public function getDelaisRealisationEstime(): ?float
	{
		return $this->delaisRealisationEstime;
	}

	public function setDelaisRealisationEstime(?float $delaisRealisationEstime): static
	{
		$this->delaisRealisationEstime = $delaisRealisationEstime;
		return $this;
	}
  	//-----15--heuresDeTravailEstime---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $heuresDeTravailEstime = null;

	public function getHeuresDeTravailEstime(): ?float
	{
		return $this->heuresDeTravailEstime;
	}

	public function setHeuresDeTravailEstime(?float $heuresDeTravailEstime): static
	{
		$this->heuresDeTravailEstime = $heuresDeTravailEstime;
		return $this;
	}
  	//-----16--delaisRealisationReel---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $delaisRealisationReel = null;

	public function getDelaisRealisationReel(): ?float
	{
		return $this->delaisRealisationReel;
	}

	public function setDelaisRealisationReel(?float $delaisRealisationReel): static
	{
		$this->delaisRealisationReel = $delaisRealisationReel;
		return $this;
	}
  	//-----17--heuresDeTravailReel---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $heuresDeTravailReel = null;

	public function getHeuresDeTravailReel(): ?float
	{
		return $this->heuresDeTravailReel;
	}

	public function setHeuresDeTravailReel(?float $heuresDeTravailReel): static
	{
		$this->heuresDeTravailReel = $heuresDeTravailReel;
		return $this;
	}
    //-----18--lieu---string--------
    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }
    //-----19--userCreat---int----------
    #[ORM\Column( nullable: true)]
    private ?int $userCreat = null;
    
	public function getUserCreat(): ?int
    {
        return $this->userCreat;
    }
    public function setUserCreat(int $userCreat): static
    {
        $this->userCreat = $userCreat;
        return $this;
    }
    //-----20--userVal---int----------
    #[ORM\Column( nullable: true)]
    private ?int $userVal = null;
    
	public function getUserVal(): ?int
    {
        return $this->userVal;
    }
    public function setUserVal(int $userVal): static
    {
        $this->userVal = $userVal;
        return $this;
    }
    //-----21--responsableTache---int----------
    #[ORM\Column( nullable: true)]
    private ?int $responsableTache = null;
    
	public function getResponsableTache(): ?int
    {
        return $this->responsableTache;
    }
    public function setResponsableTache(int $responsableTache): static
    {
        $this->responsableTache = $responsableTache;
        return $this;
    }
    //-----22--referentTech---int----------
    #[ORM\Column( nullable: true)]
    private ?int $referentTech = null;
    
	public function getReferentTech(): ?int
    {
        return $this->referentTech;
    }
    public function setReferentTech(int $referentTech): static
    {
        $this->referentTech = $referentTech;
        return $this;
    }
    //-----23--horairesTravail---int----------
    #[ORM\Column( nullable: true)]
    private ?int $horairesTravail = null;
    
	public function getHorairesTravail(): ?int
    {
        return $this->horairesTravail;
    }
    public function setHorairesTravail(int $horairesTravail): static
    {
        $this->horairesTravail = $horairesTravail;
        return $this;
    }
    //-----24--status---int----------
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


	//-------25--rem---------------------------------
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
	//-------26--valid---------------------------------
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
