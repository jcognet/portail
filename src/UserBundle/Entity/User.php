<?php

namespace UserBundle\Entity;

use CommunBundle\Entity\SuiviDevise;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
     * @var SuiviDevise
     *
     * @ORM\OneToMany(targetEntity="CommunBundle\Entity\SuiviDevise", mappedBy="user")
     */
    private $listeDevises;


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
     * @param \CommunBundle\Entity\SuiviDevise $listeDevise
     *
     * @return User
     */
    public function addListeDevise(\CommunBundle\Entity\SuiviDevise $listeDevise)
    {
        $this->listeDevises[] = $listeDevise;

        return $this;
    }

    /**
     * Remove listeDevise
     *
     * @param \CommunBundle\Entity\SuiviDevise $listeDevise
     */
    public function removeListeDevise(\CommunBundle\Entity\SuiviDevise $listeDevise)
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
}
