<?php
namespace LivreBundle\Traits;

use LivreBundle\Entity\Synonyme;

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
     * @param \LivreBundle\Entity\Synonyme $synonyme
     *
     * @return Editeur
     */
    public function addSynonyme(\LivreBundle\Entity\Synonyme $synonyme)
    {
        $this->listeSynonymes[] = $synonyme;

        return $this;
    }

    /**
     * Remove Synonyme
     *
     * @param \LivreBundle\Entity\Synonyme $synonyme
     */
    public function removeSynonyme(\LivreBundle\Entity\Synonyme $synonyme)
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