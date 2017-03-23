<?php
namespace BookBundle\Traits;

use BookBundle\Entity\Synonyme;

/**
 * Class servant à gérer les synonymes
 * Class SynonymeTrait
 */
trait SynonymeTrait
{

    /**
     * Liste des synonymes
     * @var Synonyme[]
     */
    protected $listeSynonymes = array();

    /**
     * Add synonyme
     *
     * @param \BookBundle\Entity\Synonyme $synonyme
     *
     * @return Editeur
     */
    public function addSynonyme(\BookBundle\Entity\Synonyme $synonyme)
    {
        $this->listeSynonymes[] = $synonyme;

        return $this;
    }

    /**
     * Remove Synonyme
     *
     * @param \BookBundle\Entity\Synonyme $synonyme
     */
    public function removeSynonyme(\BookBundle\Entity\Synonyme $synonyme)
    {
        $listeSynonyme = array();
        foreach ($this->listeSynonymes as $s) {
            $ajoutSynonyme = true;
            if ($s->getId() != $synonyme->getId()) {
                $listeSynonyme[] = $s;
            }
        }
        $this->listeSynonymes = $listeSynonyme;
    }

    /**
     * @return Synonyme[]
     */
    public function getListeSynonymes()
    {
        return $this->listeSynonymes;
    }

    /**
     * @return Synonyme[]
     */
    public function getSynonymes()
    {
        return $this->listeSynonymes;
    }
}