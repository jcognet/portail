<?php

namespace BookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BaseLivre
 *
 * @ORM\Table(name="base_livre")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\BaseLivreRepository")
 */
class BaseLivre
{
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
     * @ORM\Column(name="id_google", type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="google_link", type="string", length=255, nullable=true)
     */
    private $googleLink;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="datetime", nullable=true)
     */
    private $datePublication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn10", type="string", length=20, nullable=true)
     */
    private $isbn10;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn13", type="string", length=20, nullable=true)
     */
    private $isbn13;

    /**
     * @var int
     *
     * @ORM\Column(name="nombrePages", type="smallint", nullable=true)
     */
    private $nombrePages;

    /**
     * @var float
     *
     * @ORM\Column(name="hauteur", type="float", nullable=true)
     */
    private $hauteur;

    /**
     * @var float
     *
     * @ORM\Column(name="largeur", type="float", nullable=true)
     */
    private $largeur;

    /**
     * @var float
     *
     * @ORM\Column(name="epaisseur", type="float", nullable=true)
     */
    private $epaisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", nullable=true)
     */
    private $prix;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Auteur", inversedBy="livres")
     */
    private $auteurs;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Editeur", inversedBy="livres")
     */
    private $editeur;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Categorie")
     */
    private $categories;

    /**
     * @Gedmo\Slug(fields={"titre"})
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
     * Set titre
     *
     * @param string $titre
     *
     * @return BaseLivre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return BaseLivre
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BaseLivre
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
     * Set isbn10
     *
     * @param string $isbn10
     *
     * @return BaseLivre
     */
    public function setIsbn10($isbn10)
    {
        $this->isbn10 = $isbn10;

        return $this;
    }

    /**
     * Get isbn10
     *
     * @return string
     */
    public function getIsbn10()
    {
        return $this->isbn10;
    }

    /**
     * Set isbn13
     *
     * @param string $isbn13
     *
     * @return BaseLivre
     */
    public function setIsbn13($isbn13)
    {
        $this->isbn13 = $isbn13;

        return $this;
    }

    /**
     * Get isbn13
     *
     * @return string
     */
    public function getIsbn13()
    {
        return $this->isbn13;
    }

    /**
     * Set nombrePages
     *
     * @param integer $nombrePages
     *
     * @return BaseLivre
     */
    public function setNombrePages($nombrePages)
    {
        $this->nombrePages = $nombrePages;

        return $this;
    }

    /**
     * Get nombrePages
     *
     * @return int
     */
    public function getNombrePages()
    {
        return $this->nombrePages;
    }

    /**
     * Set hauteur
     *
     * @param float $hauteur
     *
     * @return BaseLivre
     */
    public function setHauteur($hauteur)
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    /**
     * Get hauteur
     *
     * @return float
     */
    public function getHauteur()
    {
        return $this->hauteur;
    }

    /**
     * Set largeur
     *
     * @param float $largeur
     *
     * @return BaseLivre
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur
     *
     * @return float
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    /**
     * Set epaisseur
     *
     * @param float $epaisseur
     *
     * @return BaseLivre
     */
    public function setEpaisseur($epaisseur)
    {
        $this->epaisseur = $epaisseur;

        return $this;
    }

    /**
     * Get epaisseur
     *
     * @return float
     */
    public function getEpaisseur()
    {
        return $this->epaisseur;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return BaseLivre
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return BaseLivre
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return BaseLivre
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set googleLink
     *
     * @param string $googleLink
     *
     * @return BaseLivre
     */
    public function setGoogleLink($googleLink)
    {
        $this->googleLink = $googleLink;

        return $this;
    }

    /**
     * Get googleLink
     *
     * @return string
     */
    public function getGoogleLink()
    {
        return $this->googleLink;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return BaseLivre
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
     * Add auteur
     *
     * @param \BookBundle\Entity\Auteur $auteur
     *
     * @return BaseLivre
     */
    public function addAuteur(\BookBundle\Entity\Auteur $auteur)
    {
        $this->auteurs[] = $auteur;

        return $this;
    }


    /**
     * Remove auteur
     *
     * @param \BookBundle\Entity\Auteur $auteur
     */
    public function removeAuteur(\BookBundle\Entity\Auteur $auteur)
    {
        $this->auteurs->removeElement($auteur);
    }

    /**
     * Get auteurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuteurs()
    {
        return $this->auteurs;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set editeur
     *
     * @param \BookBundle\Entity\Editeur $editeur
     *
     * @return BaseLivre
     */
    public function setEditeur(\BookBundle\Entity\Editeur $editeur = null)
    {
        $this->editeur = $editeur;

        return $this;
    }

    /**
     * Get editeur
     *
     * @return \BookBundle\Entity\Editeur
     */
    public function getEditeur()
    {
        return $this->editeur;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return BaseLivre
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

    /**
     * Add Categorie
     *
     * @param \BookBundle\Entity\Categorie $Categorie
     *
     * @return BaseLivre
     */
    public function addCategorie(\BookBundle\Entity\Categorie $Categorie)
    {
        $this->categories[] = $Categorie;

        return $this;
    }

    /**
     * Remove Categorie
     *
     * @param \BookBundle\Entity\Categorie $Categorie
     */
    public function removeCategorie(\BookBundle\Entity\Categorie $Categorie)
    {
        $this->categories->removeElement($Categorie);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add category
     *
     * @param \BookBundle\Entity\Categorie $category
     *
     * @return BaseLivre
     */
    public function addCategory(\BookBundle\Entity\Categorie $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \BookBundle\Entity\Categorie $category
     */
    public function removeCategory(\BookBundle\Entity\Categorie $category)
    {
        $this->categories->removeElement($category);
    }
}
