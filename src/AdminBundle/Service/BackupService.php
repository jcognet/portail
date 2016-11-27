<?php

namespace AdminBundle\Service;

use CommunBundle\Traits\OutputTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Templating\Storage\FileStorage;

/**
 * Class BackupService
 * @package AdminBundle\Service
 */
class BackupService
{
    use  OutputTrait;

    /**
     * Répertoire d'enregistrement des fichiers de back
     * @var string|null
     */
    private $repertoire = null;

    private $dureeBackUp = 0;

    /**
     * BackupService constructor.
     * @param $kernelDir
     * @param $backUpDirectory
     */
    public function __construct($kernelDir, $backUpDirectory, $dureeBackUp)
    {
        $this->creeRepertoire($kernelDir, $backUpDirectory);
        $this->dureeBackUp = $dureeBackUp;
    }

    /**
     * Calcule et crée le répertoire de back-up
     * @param $kernelDir
     * @param $backUpDirectory
     */
    private function creeRepertoire($kernelDir, $backUpDirectory)
    {
        // Nom du répertoire de backup
        $this->repertoire = $kernelDir . $backUpDirectory;
        $fs               = new Filesystem();
        if (!$fs->exists($this->repertoire)) {
            $fs->mkdir($this->repertoire);
        }
        $this->repertoire = realpath($this->repertoire);
        if (substr($this->repertoire, -1) != '/' && substr($this->repertoire, -1) != '\\') {
            $this->repertoire .= DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Nettoie le répertoire de backup
     */
    public function nettoieRepertoire()
    {
        $finder = new Finder();
        $fs     = new Filesystem();
        foreach ($finder->files()->in($this->repertoire)->date('>' . $this->dureeBackUp . 'days') as $file) {
            $this->ecrit('Suppression de : ' . $file);
            $fs->remove($file);
        }
    }

    /**
     * @return null|string
     */
    public function getRepertoire()
    {
        return $this->repertoire;
    }

}

