<?php

namespace BookBundle\Entity;

use BookBundle\Traits\SynonymeTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\CategorieRepository")
 */
class Categorie
{

    use SynonymeTrait;

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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="referenceGoogle", type="string", length=255)
     */
    private $referenceGoogle;

    /**
     * @Gedmo\Slug(fields={"referenceGoogle"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug = null;


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
     * @return Categorie
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
     * Set referenceGoogle
     *
     * @param string $referenceGoogle
     *
     * @return Categorie
     */
    public function setReferenceGoogle($referenceGoogle)
    {
        $this->referenceGoogle = $referenceGoogle;

        return $this;
    }

    /**
     * Get referenceGoogle
     *
     * @return string
     */
    public function getReferenceGoogle()
    {
        return $this->referenceGoogle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Categorie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
