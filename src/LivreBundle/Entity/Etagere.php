<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LivreBundle\Interfaces\LieuInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Etagere
 *
 * @ORM\Table(name="etagere")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\EtagereRepository")
 */
class Etagere implements LieuInterface
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
     * @var \LivreBundle\Entity\Meuble
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Meuble", inversedBy="etageres")
     * @ORM\JoinColumn(name="meuble_id", referencedColumnName="id", nullable= true)
     */
    private $meuble;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="etageres")
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
     * Set meuble
     *
     * @param \LivreBundle\Entity\Meuble $meuble
     *
     * @return Etagere
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Etagere
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Etagere
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
}
