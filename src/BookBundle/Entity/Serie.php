<?php

namespace BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Serie
 *
 * @ORM\Table(name="serie")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\SerieRepository")
 */
class Serie
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
     * @ORM\Column(name="referenceGoogle", type="string", length=255, unique=true)
     */
    private $referenceGoogle;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var Serie
     *
     * @ORM\OneToMany(targetEntity="BaseLivre", mappedBy="serie")
     */
    private $livres;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Serie
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
     * @return Serie
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
     * Set referenceGoogle
     *
     * @param string $referenceGoogle
     *
     * @return Serie
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
}
