<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meuble
 *
 * @ORM\Table(name="meuble")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\MeubleRepository")
 */
class Meuble
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
     * @var \LivreBundle\Entity\Piece
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Piece", inversedBy="meubles")
     */
    private $piece;


    /**
     * @var \LivreBundle\Entity\Etagere
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Etagere", mappedBy="meuble")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Meuble
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

