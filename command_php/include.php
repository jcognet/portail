<?php
/**
 * Execute une commande Symfony
 * @param $commande
 */
function executeSymfonyCommande($commande)
{
    //TODO : ajouter le path de PHP dans le fichier de conf Symfony et le lire
    //$phpPath = '/usr/local/php7.1/bin/php';
    $phpPath = 'php';
    //TODO : ajouter env == prod
    $cmd = $phpPath . ' ' . __DIR__ . '/../bin/console ' . $commande;
    $now = new \DateTime();
    echo $cmd . "\n";
    $output = array();
    echo $now->format('d/m/Y - H:i:s') . "\n";
    exec($cmd, $output);
    echo implode("\n", $output);
}