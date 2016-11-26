<?php

namespace CommunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Batch
 *
 * @ORM\Table(name="batch")
 * @ORM\Entity(repositoryClass="CommunBundle\Repository\BatchRepository")
 */
class Batch
{
    const TYPE_IMPORT_DEVISE  = "import_devise";
    const TYPE_ALERTE_ENVOYEE = "alerte";

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
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    /**
     * Batch constructor.
     */
    public function __construct()
    {
        $this->dateDebut = new \DateTime();
    }


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
     * @return Batch
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
     * Set fin
     *
     * @param boolean $fin
     *
     * @return Batch
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return bool
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Batch
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }


    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Batch
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Batch
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }


    /**
     * Calcule la durée du traitement.
     * @return string
     */
    public function getDuree()
    {
        if ($this->getDateFin() instanceof \DateTime) {
            $dt = $this->getDateFin()->diff($this->getDateDebut());
        } else {
            $now = new \DateTime();
            $dt  = $now->diff($this->getDateDebut());
        }
        $outDuree = $dt->format('%Im%Ss');
        if ($dt->h > 0) {
            $outDuree = $dt->format('%h') . 'h' . $outDuree;
        }
        return $outDuree;
    }

    /**
     * Indique si le batch est long
     * @return bool
     */
    public function estLong(){
        if ($this->getDateFin() instanceof \DateTime) {
            $dt = $this->getDateFin()->diff($this->getDateDebut());
            return ($dt->format('%I')>5);
        } else {
            $now = new \DateTime();
            $dt  = $now->diff($this->getDateDebut());
            return ($dt->format('%I')>5);
        }
    }

    /**
     * Récupère l'icone du batch
     * @return mixed
     */
    public function getIcone()
    {
        return self::getListeIcones()[$this->getType()];
    }

    /***
     * Liste des icones des batchs suivant le type de batch
     * @return array
     */
    public static function getListeIcones()
    {
        return [
            self::TYPE_ALERTE_ENVOYEE => 'envelope',
            self::TYPE_IMPORT_DEVISE  => 'euro',
        ];
    }
}
