<?php


namespace App\Entity\personnel;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class Crf
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
     //-----2--refMpo---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refMpo = null;
    
	public function getRefMpo(): ?int
    {
        return $this->refMpo;
    }
    public function setRefMpo(int $refMpo): static
    {
        $this->refMpo = $refMpo;
        return $this;
    }
    //-----3--date---string--------
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
 	//-----4--duree---float--------
	#[ORM\Column(type: 'float', nullable: true)]
	private ?float $duree = null;

	public function getDuree(): ?float
	{
		return $this->duree;
	}

	public function setDuree(?float $duree): static
	{
		$this->duree = $duree;
		return $this;
	}
   //-----5--formateur---int----------
    #[ORM\Column( nullable: true)]
    private ?int $formateur = null;
    
	public function getFormateur(): ?int
    {
        return $this->formateur;
    }
    public function setFormateur(int $formateur): static
    {
        $this->formateur = $formateur;
        return $this;
    }
 	//-------6--commentaire---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;
	 public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
    //-----7--objectifAtteint---int----------
    #[ORM\Column( nullable: true)]
    private ?int $objectifAtteint = null;
    
	public function getObjectifAtteint(): ?int
    {
        return $this->objectifAtteint;
    }
    public function setObjectifAtteint(int $objectifAtteint): static
    {
        $this->objectifAtteint = $objectifAtteint;
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
