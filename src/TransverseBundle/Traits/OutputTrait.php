<?php
namespace TransverseBundle\Traits;

/**
 * Class servant à écrire dans les commandes
 * Class OutputTrait
 */
trait OutputTrait
{
    /**
     * @var null|\Symfony\Component\Console\Output\OutputInterface
     */
    private $output = null;

    /**
     * Set l'output
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return $this
     */
    public function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Ecrit un message de succès
     * @param $texte
     */
    public function ecritSucces($texte)
    {
        if(!is_null($this->output))
            $this->output->writeln('<info>'.$texte.'</info>');
    }

    /**
     * Ecrit un message d'erreur
     * @param $texte
     */
    public function ecritErreur($texte)
    {
        if(!is_null($this->output))
            $this->output->writeln('<error>'.$texte.'</error>');
    }

    /**
     * Ecrit tout court
     * @param $texte
     */
    public function ecrit($texte)
    {
        if(!is_null($this->output))
            $this->output->writeln($texte);
    }
}