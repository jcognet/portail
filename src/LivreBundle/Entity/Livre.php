<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livre
 *
 * @ORM\Table(name="livre")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\LivreRepository")
 */
class Livre
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", nullable=true)
     */
    private $prix;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="listeLivres")
     */
    private $proprietaire;


    /**
     * @var \LivreBundle\Entity\Maison
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Maison", inversedBy="listeLivres")
     */
    private $maison;


    /**
     * @var \LivreBundle\Entity\Piece
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Piece", inversedBy="listeLivres")
     */
    private $piece;

    /**
     * @var \LivreBundle\Entity\Meuble
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Meuble", inversedBy="listeLivres")
     */
    private $meuble;

    /**
     * @var \LivreBundle\Entity\Etagere
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Etagere", inversedBy="listeLivres")
     */
    private $etagere;


    /**
     * @var \LivreBundle\Entity\BaseLivre
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\BaseLivre")
     */
    private $baseLivre;


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
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     *
     * @return Livre
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set dateAction
     *
     * @param \DateTime $dateAction
     *
     * @return Livre
     */
    public function setDateAction($dateAction)
    {
        $this->dateAction = $dateAction;

        return $this;
    }

    /**
     * Get dateAction
     *
     * @return \DateTime
     */
    public function getDateAction()
    {
        return $this->dateAction;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return Livre
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Livre
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

    public function __construct()
    {
        $this->dateAjout = new \DateTime();
    }

    /**
     * Set proprietaire
     *
     * @param \UserBundle\Entity\User $proprietaire
     *
     * @return Livre
     */
    public function setProprietaire(\UserBundle\Entity\User $proprietaire = null)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return \UserBundle\Entity\User
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * Set baseLivre
     *
     * @param \LivreBundle\Entity\BaseLivre $baseLivre
     *
     * @return Livre
     */
    public function setBaseLivre(\LivreBundle\Entity\BaseLivre $baseLivre = null)
    {
        $this->baseLivre = $baseLivre;

        return $this;
    }

    /**
     * Get baseLivre
     *
     * @return \LivreBundle\Entity\BaseLivre
     */
    public function getBaseLivre()
    {
        return $this->baseLivre;
    }

    /**
     * Set maison
     *
     * @param \LivreBundle\Entity\Maison $maison
     *
     * @return Livre
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
     * Set piece
     *
     * @param \LivreBundle\Entity\Piece $piece
     *
     * @return Livre
     */
    public function setPiece(\LivreBundle\Entity\Piece $piece = null)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return \LivreBundle\Entity\Piece
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Set meuble
     *
     * @param \LivreBundle\Entity\Meuble $meuble
     *
     * @return Livre
     */
    public function setMeuble(\LivreBundle\Entity\Meuble $meuble = null)
    {
        $this->meuble = $meuble;

        return $this;
    }

    /**
     * Get meuble
     *
     * @return \LivreBundle\Entity\Meuble
     */
    public function getMeuble()
    {
        return $this->meuble;
    }

    /**
     * Set etagere
     *
     * @param \LivreBundle\Entity\Etagere $etagere
     *
     * @return Livre
     */
    public function setEtagere(\LivreBundle\Entity\Etagere $etagere = null)
    {
        $this->etagere = $etagere;

        return $this;
    }

    /**
     * Get etagere
     *
     * @return \LivreBundle\Entity\Etagere
     */
    public function getEtagere()
    {
        return $this->etagere;
    }

    /**
     * @return LieuInteface
     */
    public function getLieu()
    {
        if (false === is_null($this->getEtagere())) {
            return $this->getEtagere();
        }
        if (false === is_null($this->getPiece())) {
            return $this->getPiece();
        }
        if (false === is_null($this->getMeuble())) {
            return $this->getMeuble();
        }
        if (false === is_null($this->getMaison())) {
            return $this->getMaison();
        }
        return null;
    }
}
