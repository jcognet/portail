<?php

namespace DeviseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * suivi_devise
 *
 * @ORM\Table(name="suivi_devise")
 * @ORM\Entity(repositoryClass="DeviseBundle\Repository\SuiviDeviseRepository")
 */
class SuiviDevise
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
     * @ORM\Column(name="seuil_min", type="decimal", precision=10, scale=2)
     */
    private $seuilMin;

    /**
     * @var string
     *
     * @ORM\Column(name="seuil_max", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $seuilMax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_alerte", type="datetime", nullable=true)
     */
    private $dateAlerte;

    /**
     * @var Devise
     *
     * @ORM\ManyToOne(targetEntity="Devise")
     */
    private $devise;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="listeDevises")
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
     * Set seuilMin
     *
     * @param string $seuilMin
     *
     * @return suivi_devise
     */
    public function setSeuilMin($seuilMin)
    {
        $this->seuilMin = $seuilMin;

        return $this;
    }

    /**
     * Get seuilMin
     *
     * @return string
     */
    public function getSeuilMin()
    {
        return $this->seuilMin;
    }



    /**
     * Set dateAlerte
     *
     * @param \DateTime $dateAlerte
     *
     * @return suivi_devise
     */
    public function setDateAlerte($dateAlerte)
    {
        $this->dateAlerte = $dateAlerte;

        return $this;
    }

    /**
     * Get dateAlerte
     *
     * @return \DateTime
     */
    public function getDateAlerte()
    {
        return $this->dateAlerte;
    }

    /**
     * Set seuilMax
     *
     * @param string $seuilMax
     *
     * @return suivi_devise
     */
    public function setSeuilMax($seuilMax)
    {
        $this->seuilMax = $seuilMax;

        return $this;
    }

    /**
     * Get seuilMax
     *
     * @return string
     */
    public function getSeuilMax()
    {
        return $this->seuilMax;
    }

    /**
     * Set devise
     *
     * @param \DeviseBundle\Entity\Devise $devise
     *
     * @return suiviDevise
     */
    public function setDevise(\DeviseBundle\Entity\Devise $devise = null)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return \DeviseBundle\Entity\Devise
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return suiviDevise
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
