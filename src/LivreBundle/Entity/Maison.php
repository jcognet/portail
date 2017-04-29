<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maison
 *
 * @ORM\Table(name="maison")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\MaisonRepository")
 */
class Maison
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var \LivreBundle\Entity\Piece
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Piece", mappedBy="maison")
     */
    private $pieces;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="maisons")
     */
    private $user;


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
     * @return Maison
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Maison
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pieces = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add piece
     *
     * @param \LivreBundle\Entity\Piece $piece
     *
     * @return Maison
     */
    public function addPiece(\LivreBundle\Entity\Piece $piece)
    {
        $this->pieces[] = $piece;

        return $this;
    }

    /**
     * Remove piece
     *
     * @param \LivreBundle\Entity\Piece $piece
     */
    public function removePiece(\LivreBundle\Entity\Piece $piece)
    {
        $this->pieces->removeElement($piece);
    }

    /**
     * Get pieces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Maison
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
