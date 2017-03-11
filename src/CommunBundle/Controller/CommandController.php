<?php

namespace CommunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\Response;

class CommandController extends Controller
{
    public function coursGetAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'cours:get',
        ));
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }

    public function coursGetPeriodeAction()
    {
        return $this->render('CommunBundle:Command:cours_get_periode.html.twig', array(
            // ...
        ));
    }

    public function alertSendAction()
    {
        return $this->render('CommunBundle:Command:alert_send.html.twig', array(
            // ...
        ));
    }

}
