<?php

namespace CommunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="CommunBundle\Repository\NewsRepository")
 */
class News
{
    /**
     * Constante pour les types de nouvelles pour Changesous
     */
    const TYPE_NEWS_CHANGESOUS = "changesous";

    /**
     * Constante pour les types de nouvelles pour Alex
     */
    const TYPE_NEWS_ALEX = "alex";

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
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="corps", type="text")
     */
    private $corps;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_mise_en_ligne", type="datetime")
     */
    private $dateMiseEnLigne;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type = "";


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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return News
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return News
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set corps
     *
     * @param string $corps
     *
     * @return News
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set dateMiseEnLigne
     *
     * @param \DateTime $dateMiseEnLigne
     *
     * @return News
     */
    public function setDateMiseEnLigne($dateMiseEnLigne)
    {
        $this->dateMiseEnLigne = $dateMiseEnLigne;

        return $this;
    }

    /**
     * Get dateMiseEnLigne
     *
     * @return \DateTime
     */
    public function getDateMiseEnLigne()
    {
        return $this->dateMiseEnLigne;
    }

    function __construct()
    {
        $this->dateCreation    = new \DateTime();
        $this->dateMiseEnLigne = new \DateTime("+5 hours"); // La news est publiée plus tard par défaut
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return News
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
     * Liste toutes les types de news possibles
     * @return array
     */
    public static function getListeTypeNews()
    {
        return array(
            self::TYPE_NEWS_CHANGESOUS => 'Changeous',
            self::TYPE_NEWS_ALEX       => 'Alex'
        );
    }
}
