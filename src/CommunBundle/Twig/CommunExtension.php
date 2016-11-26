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
     * CommunExtension constructor.
     * @param RequestStack $rs
     */
    public function __construct(RequestStack $rs)
    {
        $this->rs = $rs;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('modeDev', array($this, 'modeDev')),
        );
    }

    public function modeDev()
    {
        return is_int(strpos($this->rs->getCurrentRequest()->getRequestUri(), 'dev.changesous.com'));
    }

}