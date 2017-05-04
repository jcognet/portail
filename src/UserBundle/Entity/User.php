<?php

namespace UserBundle\Entity;

use DeviseBundle\Entity\SuiviDevise;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var SuiviDevise[]
     *
     * @ORM\OneToMany(targetEntity="DeviseBundle\Entity\SuiviDevise", mappedBy="user")
     */
    private $listeDevises;


    /**
     * @var bool
     *
     * @ORM\Column(name="send_mail_for_alert", type="boolean")
     */
    private $sendMailForAlert = true;

    /**
     * @var int
     *
     * @ORM\Column(name="jour_avant_relance", type="smallint")
     */
    private $joursAvantRelance = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="jours_prochaine_alerte", type="datetime", nullable=true)
     */
    private $jourProchaineAlerte = null;


    /**
     * @var Livre[]
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Livre", mappedBy="proprietaire")
     * @ORM\OrderBy({"dateAjout" = "DESC"})
     */
    private $listeLivres;


    /**
     * @var \LivreBundle\Entity\Maison
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Maison", mappedBy="user")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $maisons;


    /**
     * @var \LivreBundle\Entity\Piece
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Piece", mappedBy="user")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $pieces;


    /**
     * @var \LivreBundle\Entity\Meuble
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Meuble", mappedBy="user")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $meubles;


    /**
     * @var \LivreBundle\Entity\Etagere
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Etagere", mappedBy="user")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $etageres;


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
     * Add listeDevise
     *
     * @param \DeviseBundle\Entity\SuiviDevise $listeDevise
     *
     * @return User
     */
    public function addListeDevise(\DeviseBundle\Entity\SuiviDevise $listeDevise)
    {
        $this->listeDevises[] = $listeDevise;

        return $this;
    }

    /**
     * Remove listeDevise
     *
     * @param \DeviseBundle\Entity\SuiviDevise $listeDevise
     */
    public function removeListeDevise(\DeviseBundle\Entity\SuiviDevise $listeDevise)
    {
        $this->listeDevises->removeElement($listeDevise);
    }

    /**
     * Get listeDevises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeDevises()
    {
        return $this->listeDevises;
    }

    /**
     * Set sendMailForAlert
     *
     * @param boolean $sendMailForAlert
     *
     * @return User
     */
    public function setSendMailForAlert($sendMailForAlert)
    {
        $this->sendMailForAlert = $sendMailForAlert;

        return $this;
    }

    /**
     * Get sendMailForAlert
     *
     * @return boolean
     */
    public function getSendMailForAlert()
    {
        return $this->sendMailForAlert;
    }

    /**
     * Set joursAvantRelance
     *
     * @param integer $joursAvantRelance
     *
     * @return User
     */
    public function setJoursAvantRelance($joursAvantRelance)
    {
        $this->joursAvantRelance = $joursAvantRelance;

        return $this;
    }

    /**
     * Get joursAvantRelance
     *
     * @return integer
     */
    public function getJoursAvantRelance()
    {
        return $this->joursAvantRelance;
    }

    /**
     * Set jourProchaineAlerte
     *
     * @param \DateTime $jourProchaineAlerte
     *
     * @return User
     */
    public function setJourProchaineAlerte($jourProchaineAlerte)
    {
        $this->jourProchaineAlerte = $jourProchaineAlerte;

        return $this;
    }

    /**
     * Get jourProchaineAlerte
     *
     * @return \DateTime
     */
    public function getJourProchaineAlerte()
    {
        return $this->jourProchaineAlerte;
    }

    /**
     * Add listeLivre
     *
     * @param \LivreBundle\Entity\Livre $listeLivre
     *
     * @return User
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

    /**
     * Add maison
     *
     * @param \LivreBundle\Entity\Maison $maison
     *
     * @return User
     */
    public function addMaison(\LivreBundle\Entity\Maison $maison)
    {
        $this->maisons[] = $maison;

        return $this;
    }

    /**
     * Remove maison
     *
     * @param \LivreBundle\Entity\Maison $maison
     */
    public function removeMaison(\LivreBundle\Entity\Maison $maison)
    {
        $this->maisons->removeElement($maison);
    }

    /**
     * Get maisons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaisons()
    {
        return $this->maisons;
    }

    /**
     * Add piece
     *
     * @param \LivreBundle\Entity\Piece $piece
     *
     * @return User
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
     * Add meuble
     *
     * @param \LivreBundle\Entity\Meuble $meuble
     *
     * @return User
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
     * Add etagere
     *
     * @param \LivreBundle\Entity\Etagere $etagere
     *
     * @return User
     */
    public function addEtagere(\LivreBundle\Entity\Etagere $etagere)
    {
        $this->etageres[] = $etagere;

        return $this;
    }

    /**
     * Remove etagere
     *
     * @param \LivreBundle\Entity\Etagere $etagere
     */
    public function removeEtagere(\LivreBundle\Entity\Etagere $etagere)
    {
        $this->etageres->removeElement($etagere);
    }

    /**
     * Get etageres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtageres()
    {
        return $this->etageres;
    }

    /**
     * Returne si l'utilisateur possède au moins une pièce sans maison
     * @return bool
     */
    public function hasPieceSansMaison()
    {
        $hasPieceSansMaison = false;
        foreach ($this->getPieces() as $piece) {
            if (is_null($piece->getMaison())) {
                $hasPieceSansMaison = true;
                break;
            }
        }
        return $hasPieceSansMaison;
    }

    /**
     * Returne si l'utilisateur possède au moins un meuble sans piece
     * @return bool
     */
    public function hasMeubleSansPiece()
    {
        $hasMeubleSansPiece = false;
        foreach ($this->getMeubles() as $meuble) {
            if (is_null($meuble->getPiece())) {
                $hasMeubleSansPiece = true;
                break;
            }
        }
        return $hasMeubleSansPiece;
    }

    /**
     *  Returne si l'utilisateur possède au moins une étagère sans meuble
     * @return bool
     */
    public function hasEtagereSansMeuble()
    {
        $hasEtagereSansMeuble = false;
        foreach ($this->getEtageres() as $etagere) {
            if (is_null($etagere->getMeuble())) {
                $hasEtagereSansMeuble = true;
                break;
            }
        }
        return $hasEtagereSansMeuble;
    }

    /**
     * Retourne le référentiel lieu de la personne
     * @return array
     */
    public function getReferentielLieu()
    {
        $referentiel = array();
        foreach ($this->getMaisons() as $maison) {
            $referentiel[] = $maison;
            foreach ($maison->getPieces() as $piece) {
                $referentiel[] = $piece;
                foreach ($piece->getMeubles() as $meuble) {
                    $referentiel[] = $meuble;
                    foreach ($meuble->getEtageres() as $etagere) {
                        $referentiel[] = $etagere;
                    }
                }
            }
        }
        return $referentiel;
    }
}
