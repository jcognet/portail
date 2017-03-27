<?php

namespace CommunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoursJournee
 *
 * @ORM\Table(name="cours_journee")
 * @ORM\Entity(repositoryClass="CommunBundle\Repository\CoursJourneeRepository")
 */
class CoursJournee
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="cours", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $cours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_30_jours", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $moyenne30Jours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_60_jours", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $moyenne60Jours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_90_jours", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $moyenne90Jours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_120_jours", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $moyenne120Jours;

    /**
     * @var Devise
     *
     * @ORM\ManyToOne(targetEntity="Devise")
     */
    private $devise;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CoursJournee
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set moyenne30Jours
     *
     * @param string $moyenne30Jours
     *
     * @return CoursJournee
     */
    public function setMoyenne30Jours($moyenne30Jours)
    {
        $this->moyenne30Jours = $moyenne30Jours;

        return $this;
    }

    /**
     * Get moyenne30Jours
     *
     * @return string
     */
    public function getMoyenne30Jours()
    {
        return $this->moyenne30Jours;
    }


    /**
     * Set moyenne90Jours
     *
     * @param string $moyenne90Jours
     *
     * @return CoursJournee
     */
    public function setMoyenne90Jours($moyenne90Jours)
    {
        $this->moyenne90Jours = $moyenne90Jours;

        return $this;
    }

    /**
     * Get moyenne90Jours
     *
     * @return string
     */
    public function getMoyenne90Jours()
    {
        return $this->moyenne90Jours;
    }

    /**
     * Set moyenne120Jours
     *
     * @param string $moyenne120Jours
     *
     * @return CoursJournee
     */
    public function setMoyenne120Jours($moyenne120Jours)
    {
        $this->moyenne120Jours = $moyenne120Jours;

        return $this;
    }

    /**
     * Get moyenne120Jours
     *
     * @return string
     */
    public function getMoyenne120Jours()
    {
        return $this->moyenne120Jours;
    }

    /**
     * Set moyenne60Jours
     *
     * @param string $moyenne60Jours
     *
     * @return CoursJournee
     */
    public function setMoyenne60Jours($moyenne60Jours)
    {
        $this->moyenne60Jours = $moyenne60Jours;

        return $this;
    }

    /**
     * Get moyenne60Jours
     *
     * @return string
     */
    public function getMoyenne60Jours()
    {
        return $this->moyenne60Jours;
    }

    /**
     * Set devise
     *
     * @param \CommunBundle\Entity\Devise $devise
     *
     * @return CoursJournee
     */
    public function setDevise(\CommunBundle\Entity\Devise $devise = null)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return \CommunBundle\Entity\Devise
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set cours
     *
     * @param string $cours
     *
     * @return CoursJournee
     */
    public function setCours($cours)
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * Get cours
     *
     * @return string
     */
    public function getCours()
    {
        return $this->cours;
    }
}
