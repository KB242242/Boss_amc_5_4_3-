<?php

namespace App\Entity\administratif;

use DateTime;
use Assert\IsNull;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity()]
class AnnuaireEntreprise
{
       //-------1--id---int--refEntreprise---------------- 
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
    //-------------2--nom--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $nom = null;
    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }
    //-------------3--ville--------------------
    #[ORM\Column(length: 255,nullable: true)]
       private ?string $ville = null;
    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }
    //-------------4--pays--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $pays = null;
    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }
    //-------------5--addPostale--------------------
    #[ORM\Column(length: 255,nullable: true)]
       private ?string $addPostale = null;
    public function getAddPostale()
    {
        return $this->addPostale;
    }

    public function setAddPostale( $addPostale)
    {
        $this->addPostale = $addPostale;

        return $this;
    }
    //-------------6--siteInternet--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $siteInternet = null;
    public function getSiteInternet()
    {
        return $this->siteInternet;
    }

    public function setSiteInternet( $siteInternet)
    {
        $this->siteInternet = $siteInternet;

        return $this;
    }
    //-------------7--statusEntreprise--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $statusEntreprise = null;
    public function getStatusEntreprise()
    {
        return $this->statusEntreprise;
    }

    public function setStatusEntreprise( $statusEntreprise)
    {
        $this->statusEntreprise = $statusEntreprise;

        return $this;
    }
    //-------------8--uidNom--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $uidNom = null;
    public function getUidNom()
    {
        return $this->uidNom;
    }

    public function setUidNom( $uidNom)
    {
        $this->uidNom = $uidNom;

        return $this;
    }
    //-------------9--uidVal--------------------
    #[ORM\Column(length: 255,nullable: true)]
    private ?string $uidVal = null;
    public function getUidVal()
    {
        return $this->uidVal;
    }

    public function setUidVal($uidVal)
    {
        $this->uidVal = $uidVal;

        return $this;
    }
    //-------------10--activite--------------------
    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $activite = null;
    public function getActivite()
    {
        return $this->activite;
    }

    public function setActivite($activite)
    {
        $this->activite = $activite;

        return $this;
    }
    //-------------11--clientFourniss--------------------
    #[ORM\Column]
    private ?string $clientFourniss = null;

       public function getClientFourniss()
    {
        return $this->clientFourniss;
    }
    public function setClientFourniss($clientFourniss)
    {
        $this->clientFourniss = $clientFourniss;

        return $this;
    }
    //------------12--rem---------------------------------
    #[ORM\Column(type: Types::TEXT)]
    private ?string $rem = null;

	 public function getRem()
    {
        return $this->rem;
    }

    public function setRem($rem)
    {
        $this->rem = $rem;

        return $this;
    }
	//------------13--valid---------------------------------
    #[ORM\Column]
    private ?bool $valid = null;

   public function getValid()
    {
        return $this->valid;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }
}
