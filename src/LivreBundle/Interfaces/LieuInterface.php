<?php

namespace LivreBundle\Interfaces;

interface LieuInterface{
    public function setUser(\UserBundle\Entity\User $user = null);

    public function getUser();

    public function getNom();

    public function setNom($nom );

    public function getId();

    public function addListeLivre(\LivreBundle\Entity\Livre $listeLivre);

    public function removeListeLivre(\LivreBundle\Entity\Livre $listeLivre);

    public function getListeLivres();
}