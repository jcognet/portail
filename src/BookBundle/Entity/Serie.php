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
     * @ORM\Column(name="googleReference", type="string", length=255)
     */
    private $googleReference;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


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
     * Set googleReference
     *
     * @param string $googleReference
     *
     * @return Serie
     */
    public function setGoogleReference($googleReference)
    {
        $this->googleReference = $googleReference;

        return $this;
    }

    /**
     * Get googleReference
     *
     * @return string
     */
    public function getGoogleReference()
    {
        return $this->googleReference;
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
}
