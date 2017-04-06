<?php
/**
 * Created by PhpStorm.
 * User: Jerome
 * Date: 12/11/2016
 * Time: 11:09
 */

namespace CommunBundle\Twig;


use Symfony\Component\HttpFoundation\RequestStack;

class CommunExtension extends \Twig_Extension
{

    /**
     * Request stack
     * @var null|RequestStack
     */
    private $rs = null;

    /**
     * Url de dev'
     * @var array
     */
    private $urlDev = array();

    /**
     * CommunExtension constructor.
     * @param RequestStack $rs
     */
    public function __construct(RequestStack $rs, $urlDev)
    {
        $this->rs     = $rs;
        $this->urlDev = $urlDev;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mode_dev', array($this, 'modeDev')),
            new \Twig_SimpleFunction('espace_est_actif', array($this, 'espaceEstActif')),
            new \Twig_SimpleFunction('page_est_actif', array($this, 'pageEstActif')),
        );
    }

    /**
     * Retourne si le site est en mode dev
     * @return bool
     */
    public function modeDev()
    {
        return in_array(
            $this->rs->getCurrentRequest()->getUri(),
            $this->urlDev
        );
    }

    /**
     * Retourne si le menu est actif ou pas pour un espace donné (se base sur la route)
     * @param $espace
     * @return bool
     */
    public function espaceEstActif($espace)
    {
        $estMenuActif = false;
        // Recherche de la route courante
        $request = $this->rs->getCurrentRequest();
        $route   = $request->get('_route');

        // Les routes commencent toujours par le nom du bundle (et donc de l'espace)
        if (0 === strpos($route, $espace)) {
            $estMenuActif = true;
        } else {

        }
        return $estMenuActif;
    }

    /**
     * Retourne si le menu est actif ou pas pour un espace donné (se base sur la route)
     * @param $route
     * @return bool
     */
    public function pageEstActif($routePropose)
    {
        // Recherche de la route courante
        $request = $this->rs->getCurrentRequest();
        $route   = $request->get('_route');
        return $routePropose === $route;
    }

}