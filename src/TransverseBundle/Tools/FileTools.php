<?php

namespace TransverseBundle\Tools;


/**
 * Class CurlService, sert à gérer les appels Curls
 * @package TransverseBundle\Service
 */
class FileTools
{
    /**
     * Nettoie le nom d'un fichier
     * @param $str
     * @return mixed|string
     */
    public static function nettoieNomFichier($str)
    {
        // Source : http://www.weirdog.com/blog/php/supprimer-les-accents-des-caracteres-accentues.html
        $charset ='utf-8';
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }
}

