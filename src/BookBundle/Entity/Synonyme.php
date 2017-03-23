<?php

namespace BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Synonyme
 *
 * @ORM\Table(name="synonyme")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\SynonymeRepository")
 */
class Synonyme
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="mot", type="string", length=255)
     */
    private $mot;

    /**
     * @var string
     *
     * @ORM\Column(name="objet_id", type="integer")
     */
    private $objetId;


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
     * Set type
     *
     * @param string $type
     *
     * @return Synonyme
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set mot
     *
     * @param string $mot
     *
     * @return Synonyme
     */
    public function setMot($mot)
    {
        $this->mot = $mot;

        return $this;
    }

    /**
     * Get mot
     *
     * @return string
     */
    public function getMot()
    {
        return $this->mot;
    }

    /**
     * Set objetId
     *
     * @param integer $objetId
     *
     * @return Synonyme
     */
    public function setObjetId($objetId)
    {
        $this->objetId = $objetId;

        return $this;
    }

    /**
     * Get objetId
     *
     * @return integer
     */
    public function getObjetId()
    {
        return $this->objetId;
    }
}
