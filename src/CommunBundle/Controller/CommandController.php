<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\Devise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommandController extends Controller
{
    /**
     * Récupère le cours d'une / des devises
     * @param Request $request
     * @return Response
     */
    public function coursGetAction(Request $request)
    {
        // TODO : prendre en compte la devise et la journée comme dans la commande ?
        return $this->executeCommand('cours:get');
    }

    /**
     * Récupère les cours sur un intervalle deonné
     * @param Request $request
     * @return Response
     */
    public function coursGetPeriodeAction(Request $request)
    {
        // TODO : prendre en compte la devise et l'intervalle comme dans la commande ?
        return $this->executeCommand('cours:get-periode');

    }

    public function alertSendAction()
    {
        return $this->executeCommand('alert:send');

    }

    /**
     * Exécute une commande
     * @param $command
     * @return Response
     */
    private function executeCommand($command)
    {
        // Préparation de l'appel
        $kernel      = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        // Préparation de l'input
        $input = new ArrayInput(array(
            'command' => $command,
        ));
        // Récupération de l'output
        $output = new BufferedOutput();
        $application->run($input, $output);
        // Affichage du résultat
        return $this->render('CommunBundle:Command:affichage_resultat.html.twig', array(
            'output'   => $output->fetch(),
            'command' => $command
        ));
    }

}
