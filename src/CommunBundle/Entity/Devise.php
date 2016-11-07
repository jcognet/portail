<?php

namespace CommunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devise
 *
 * @ORM\Table(name="devise")
 * @ORM\Entity(repositoryClass="CommunBundle\Repository\DeviseRepository")
 */
class Devise
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
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="code_webservice", type="string", length=20, nullable=true)
     */
    private $codeWebservice;

    /**
     * @var string
     *
     * @ORM\Column(name="cours_jour", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $coursJour;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_30_jours", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $moyenne30Jours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="moyenne_60_jours", type="decimal", precision=10, scale=2,  nullable=true)
     */
    private $moyenne60Jours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_90_jours", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $moyenne90Jours;

    /**
     * @var string
     *
     * @ORM\Column(name="moyenne_120_jours", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $moyenne120Jours;


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
     * Set label
     *
     * @param string $label
     *
     * @return Devise
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Devise
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set coursJour
     *
     * @param string $coursJour
     *
     * @return Devise
     */
    public function setCoursJour($coursJour)
    {
        $this->coursJour = $coursJour;

        return $this;
    }

    /**
     * Get coursJour
     *
     * @return string
     */
    public function getCoursJour()
    {
        return $this->coursJour;
    }

    /**
     * Set moyenne30Jours
     *
     * @param string $moyenne30Jours
     *
     * @return Devise
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
     * Set moyenne60Jours
     *
     * @param \DateTime $moyenne60Jours
     *
     * @return Devise
     */
    public function setMoyenne60Jours($moyenne60Jours)
    {
        $this->moyenne60Jours = $moyenne60Jours;

        return $this;
    }

    /**
     * Get moyenne60Jours
     *
     * @return \DateTime
     */
    public function getMoyenne60Jours()
    {
        return $this->moyenne60Jours;
    }

    /**
     * Set moyenne90Jours
     *
     * @param string $moyenne90Jours
     *
     * @return Devise
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
     * @return Devise
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
     * Set codeWebservice
     *
     * @param string $codeWebservice
     *
     * @return Devise
     */
    public function setCodeWebservice($codeWebservice)
    {
        $this->codeWebservice = $codeWebservice;

        return $this;
    }

    /**
     * Get codeWebservice
     *
     * @return string
     */
    public function getCodeWebservice()
    {
        return $this->codeWebservice;
    }
}
