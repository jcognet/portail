<?php

namespace BookBundle\Entity;

use BookBundle\Traits\SynonymeTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Auteur
 *
 * @ORM\Table(name="auteur")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\AuteurRepository")
 */
class Auteur
{
    use SynonymeTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomComplet", type="string", length=255, nullable=true)
     */
    private $nomComplet;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="referenceGoogle", type="string", length=255, unique=true)
     */
    private $referenceGoogle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMort", type="datetime", nullable=true)
     */
    private $dateMort;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BaseLivre", mappedBy="auteurs")
     */
    private $livres;

    /**
     * @Gedmo\Slug(fields={"referenceGoogle"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug = null;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomComplet
     *
     * @param string $nomComplet
     *
     * @return Auteur
     */
    public function setNomComplet($nomComplet)
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    /**
     * Get nomComplet
     *
     * @return string
     */
    public function getNomComplet()
    {
        return $this->nomComplet;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Auteur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Auteur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Auteur
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set referenceGoogle
     *
     * @param string $referenceGoogle
     *
     * @return Auteur
     */
    public function setReferenceGoogle($referenceGoogle)
    {
        $this->referenceGoogle = $referenceGoogle;

        return $this;
    }

    /**
     * Get referenceGoogle
     *
     * @return string
     */
    public function getReferenceGoogle()
    {
        return $this->referenceGoogle;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Auteur
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Auteur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set dateMort
     *
     * @param \DateTime $dateMort
     *
     * @return Auteur
     */
    public function setDateMort($dateMort)
    {
        $this->dateMort = $dateMort;

        return $this;
    }

    /**
     * Get dateMort
     *
     * @return \DateTime
     */
    public function getDateMort()
    {
        return $this->dateMort;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Auteur
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add livre
     *
     * @param \BookBundle\Entity\BaseLivre $livre
     *
     * @return Auteur
     */
    public function addLivre(\BookBundle\Entity\BaseLivre $livre)
    {
        $this->livres[] = $livre;

        return $this;
    }

    /**
     * Remove livre
     *
     * @param \BookBundle\Entity\BaseLivre $livre
     */
    public function removeLivre(\BookBundle\Entity\BaseLivre $livre)
    {
        $this->livres->removeElement($livre);
    }

    /**
     * Get livres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivres()
    {
        return $this->livres;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Auteur
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
