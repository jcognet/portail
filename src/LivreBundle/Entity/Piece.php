<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LivreBundle\Interfaces\LieuInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Piece
 *
 * @ORM\Table(name="piece")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\PieceRepository")
 */
class Piece implements LieuInterface
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
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="etage", type="string", length=255, nullable=true))
     */
    private $etage;

    /**
     * @var \LivreBundle\Entity\Maison
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Maison", inversedBy="pieces")
     * @ORM\JoinColumn(name="maison_id", referencedColumnName="id", nullable= true)
     */
    private $maison;

    /**
     * @var \LivreBundle\Entity\Meuble
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Meuble", mappedBy="piece", cascade={"remove"})
     */
    private $meubles;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="pieces")
     */
    private $user;

    /**
     * @var \LivreBundle\Entity\Livre
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Livre", mappedBy="piece")
     */
    private $listeLivres;


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
     * @return Piece
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
        $this->meubles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set etage
     *
     * @param string $etage
     *
     * @return Piece
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;

        return $this;
    }

    /**
     * Get etage
     *
     * @return string
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Set maison
     *
     * @param \LivreBundle\Entity\Maison $maison
     *
     * @return Piece
     */
    public function setMaison(\LivreBundle\Entity\Maison $maison = null)
    {
        $this->maison = $maison;

        return $this;
    }

    /**
     * Get maison
     *
     * @return \LivreBundle\Entity\Maison
     */
    public function getMaison()
    {
        return $this->maison;
    }

    /**
     * Add meuble
     *
     * @param \LivreBundle\Entity\Meuble $meuble
     *
     * @return Piece
     */
    public function addMeuble(\LivreBundle\Entity\Meuble $meuble)
    {
        $this->meubles[] = $meuble;

        return $this;
    }

    /**
     * Remove meuble
     *
     * @param \LivreBundle\Entity\Meuble $meuble
     */
    public function removeMeuble(\LivreBundle\Entity\Meuble $meuble)
    {
        $this->meubles->removeElement($meuble);
    }

    /**
     * Get meubles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeubles()
    {
        return $this->meubles;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Piece
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

    /**
     * Add listeLivre
     *
     * @param \LivreBundle\Entity\Livre $listeLivre
     *
     * @return Piece
     */
    public function addListeLivre(\LivreBundle\Entity\Livre $listeLivre)
    {
        $this->listeLivres[] = $listeLivre;

        return $this;
    }

    /**
     * Remove listeLivre
     *
     * @param \LivreBundle\Entity\Livre $listeLivre
     */
    public function removeListeLivre(\LivreBundle\Entity\Livre $listeLivre)
    {
        $this->listeLivres->removeElement($listeLivre);
    }

    /**
     * Get listeLivres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeLivres()
    {
        return $this->listeLivres;
    }
}
