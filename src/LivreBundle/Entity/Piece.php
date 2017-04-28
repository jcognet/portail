<?php

namespace LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Piece
 *
 * @ORM\Table(name="piece")
 * @ORM\Entity(repositoryClass="LivreBundle\Repository\PieceRepository")
 */
class Piece
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
     * @ORM\Column(name="etage", type="string", length=255)
     */
    private $etage;

    /**
     * @var \LivreBundle\Entity\Maison
     *
     * @ORM\ManyToOne(targetEntity="LivreBundle\Entity\Maison", inversedBy="pieces")
     */
    private $maison;

    /**
     * @var \LivreBundle\Entity\Meuble
     *
     * @ORM\OneToMany(targetEntity="LivreBundle\Entity\Meuble", mappedBy="piece")
     */
    private $meubles;


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
}

