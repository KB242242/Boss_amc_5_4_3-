<?php


namespace App\Entity\personnel;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class Employe
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
    //-----2--nom---string--------
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

   //-----3--prenom---string--------
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

     //-----4--refSection---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refSection = null;
    
	public function getRefSection(): ?int
    {
        return $this->refSection;
    }
    public function setRefSection(int $refSection): static
    {
        $this->refSection = $refSection;
        return $this;
    }
	//-------5--fonction---TEXT--------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fonction = null;
	 public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }
   //-----6--categorie---int----------
    #[ORM\Column( nullable: true)]
    private ?int $categorie = null;
    
	public function getCategorie(): ?int
    {
        return $this->categorie;
    }
    public function setCategorie(int $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }
   //-----7--echelon---int----------
    #[ORM\Column( nullable: true)]
    private ?int $echelon = null;
    
	public function getEchelon(): ?int
    {
        return $this->echelon;
    }
    public function setEchelon(int $echelon): static
    {
        $this->echelon = $echelon;
        return $this;
    }
   //-----8--refContact---int----------
    #[ORM\Column( nullable: true)]
    private ?int $refContact = null;
    
	public function getRefContact(): ?int
    {
        return $this->refContact;
    }
    public function setRefContact(int $refContact): static
    {
        $this->refContact = $refContact;
        return $this;
    }


 	//-------9----rem---------------------------------
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
	//-------10----valid---------------------------------
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
