<?php

namespace App\Entity\system;

use App\Repository\system\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    //--------1-id---int--refUser---------------- 
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
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

    //----------------2-roles---------------
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    //----------------3-droit---------------
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $droit = null;

    public function getDroit(): ?int
    {
        return $this->droit;
    }

    public function setDroit(?int $droit): static
    {
        $this->droit = $droit;
        return $this;
    }

    //----------------4-nom---------------
    #[ORM\Column(length: 255, nullable: true)]
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

    //----------------5-prenom---------------
    #[ORM\Column(length: 255, nullable: true)]
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

    //----------------6-pseudo---------------
    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    //----------------7-initiales---------------
    #[ORM\Column(length: 255)]
    private ?string $initiales = null;

    public function getInitiales(): ?string
    {
        return $this->initiales;
    }

    public function setInitiales(?string $initiales): static
    {
        $this->initiales = $initiales;
        return $this;
    }

    //----------------8-pwd---------------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pwd = null;

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(?string $pwd): static
    {
        $this->pwd = $pwd;
        return $this;
    }

    //----------------9-ipContrainte---------------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipContrainte = null;

    public function getIpContrainte(): ?string
    {
        return $this->ipContrainte;
    }

    public function setIpContrainte(?string $ipContrainte): static
    {
        $this->ipContrainte = $ipContrainte;
        return $this;
    }

    //----------------10-nbEchecConn---------------
    #[ORM\Column(type: 'integer')]
    private ?int $nbEchecConn = null;

    public function getNbEchecConn(): ?int
    {
        return $this->nbEchecConn;
    }

    public function setNbEchecConn(int $nbEchecConn): static
    {
        $this->nbEchecConn = $nbEchecConn;
        return $this;
    }

    //----------------11-blackList---------------
    #[ORM\Column(type: 'boolean')]
    private ?bool $blackList = null;

    public function getBlackList(): ?bool
    {
        return $this->blackList;
    }

    public function setBlackList(bool $blackList): static
    {
        $this->blackList = $blackList;
        return $this;
    }

    //----------------12-dateConn---------------
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateConn = null;

    public function getDateConn(): ?string
    {
        return $this->dateConn;
    }

    public function setDateConn(?string $dateConn): static
    {
        $this->dateConn = $dateConn;
        return $this;
    }

    //----------------13-context---------------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $context = null;

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;
        return $this;
    }

    //----------------14-retContext---------------
    #[ORM\Column(type: 'boolean')]
    private ?bool $retContext = null;

    public function getRetContext(): ?bool
    {
        return $this->retContext;
    }

    public function setRetContext(bool $retContext): static
    {
        $this->retContext = $retContext;
        return $this;
    }

    //----------------15-rem---------------
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rem = null;

    public function getRem(): ?string
    {
        return $this->rem;
    }

    public function setRem(?string $rem): static
    {
        $this->rem = $rem;
        return $this;
    }

    //----------------16-valid---------------
    #[ORM\Column(type: 'boolean')]
    private ?bool $valid = null;

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): static
    {
        $this->valid = $valid;
        return $this;
    }
}
