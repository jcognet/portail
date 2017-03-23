<?php

namespace BookBundle\Entity;

use BookBundle\Traits\SynonymeTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Editeur
 *
 * @ORM\Table(name="editeur")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\EditeurRepository")
 */
class Editeur
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
     * @ORM\Column(name="referenceGoogle", type="string", length=255)
     */
    private $referenceGoogle;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFondation", type="datetime", nullable=true)
     */
    private $dateFondation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFermeture", type="datetime", nullable=true)
     */
    private $dateFermeture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BaseLivre", mappedBy="editeur")
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
     * Set referenceGoogle
     *
     * @param string $referenceGoogle
     *
     * @return Editeur
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
     * @return Editeur
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
     * Set dateFondation
     *
     * @param \DateTime $dateFondation
     *
     * @return Editeur
     */
    public function setDateFondation($dateFondation)
    {
        $this->dateFondation = $dateFondation;

        return $this;
    }

    /**
     * Get dateFondation
     *
     * @return \DateTime
     */
    public function getDateFondation()
    {
        return $this->dateFondation;
    }

    /**
     * Set dateFermeture
     *
     * @param \DateTime $dateFermeture
     *
     * @return Editeur
     */
    public function setDateFermeture($dateFermeture)
    {
        $this->dateFermeture = $dateFermeture;

        return $this;
    }

    /**
     * Get dateFermeture
     *
     * @return \DateTime
     */
    public function getDateFermeture()
    {
        return $this->dateFermeture;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Editeur
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
     * Add livre
     *
     * @param \BookBundle\Entity\BaseLivre $livre
     *
     * @return Editeur
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Editeur
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Editeur
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
