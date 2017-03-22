<?php

namespace TransverseBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use TransverseBundle\Tools\FileTools;


/**
 * Class CurlService, sert à gérer les appels Curls
 * @package TransverseBundle\Service
 */
class CurlService
{
    /**
     * Appelle une URL CURL
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function appelleCurl($url)
    {
        // Appel Curl
        $resource = curl_init();
        // Prépararation à un appel curl
        curl_setopt($resource, CURLOPT_URL, $url);
        curl_setopt($resource, CURLOPT_HEADER, false);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        // On y va !
        $resultatJSON = curl_exec($resource);
        try {
            if (false !== $resultatJSON) {
                return json_decode($resultatJSON);
            }
        } catch (\Exception $e) {
            echo curl_error($resource);
            curl_close($resource);
            throw $e;
        }
        curl_close($resource);
        throw new \Exception("Impossible de se connecter au webservice de l'url : " . $url);
    }


    /**
     * Télécharge une image dans un répertoire
     * @param $url
     * @param $pathDestination
     * @return bool|File
     * @throws \Exception
     */
    public function telechargeImage($url, $pathDestination)
    {
        // Vérification de l'existence du répertoire
        $fs         = new FileSystem();
        $repertoire = dirname($pathDestination);
        if (false === $fs->exists($repertoire)) {
            $fs->mkdir($repertoire);
        }
        // Nettoyage du nom de fichier
        $fileName =  FileTools::nettoieNomFichier(basename($pathDestination));
        // Destination finale
        $destination = $repertoire.DIRECTORY_SEPARATOR.$fileName;
        // Appel CURL
        $resource = curl_init($url);
        $image    = fopen($destination, 'wb');
        curl_setopt($resource, CURLOPT_FILE, $image);
        curl_setopt($resource, CURLOPT_HEADER, 0);
        curl_exec($resource);
        try {
            curl_close($resource);
            fclose($image);
            return true;
        } catch (\Exception $e) {
            echo curl_error($resource);
            curl_close($resource);
            throw $e;
        }
        return new File($pathDestination);
    }
}

