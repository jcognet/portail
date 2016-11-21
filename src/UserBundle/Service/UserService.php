<?php

namespace UserBundle\Service;


use UserBundle\Entity\User;

class UserService
{

    /**
     * salt secret de symfony
     * @var string
     */
    private  $secret = "";

    /**
     * UserService constructor.
     * @param $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * calcule le hashage
     * @param User $user
     * @return bool|string
     */
    public function calculeHachage(User $user)
    {
        return $this->chiffre($user);
    }

    /**
     * Vérifie le hashage
     * @param User $user
     * @param $texte
     * @return bool
     */
    public function verifieHachage(User $user, $texte)
    {
        return $this->chiffre($user) == $texte;
    }

    /**
     * chiffre un utilisateur
     * @param User $user
     * @return bool|string
     */
    private function chiffre(User $user)
    {
        return crypt ($user->getEmail() . "#" . $user->getId() . "#" . $user->getPassword(), $this->secret ) ;
    }
}
