<?php

namespace UserBundle\Entity;

use CommunBundle\Entity\Devise;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
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
     * @var Devise
     *
     * @ORM\OneToMany(targetEntity="CommunBundle\Entity\suiviDevise", mappedBy="user")
     */
    private $deviseSuivies;


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
     * Add deviseSuivy
     *
     * @param \CommunBundle\Entity\suiviDevise $deviseSuivy
     *
     * @return User
     */
    public function addDeviseSuivy(\CommunBundle\Entity\suiviDevise $deviseSuivy)
    {
        $this->deviseSuivies[] = $deviseSuivy;

        return $this;
    }

    /**
     * Remove deviseSuivy
     *
     * @param \CommunBundle\Entity\suiviDevise $deviseSuivy
     */
    public function removeDeviseSuivy(\CommunBundle\Entity\suiviDevise $deviseSuivy)
    {
        $this->deviseSuivies->removeElement($deviseSuivy);
    }

    /**
     * Get deviseSuivies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeviseSuivies()
    {
        return $this->deviseSuivies;
    }
}
