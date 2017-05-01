<?php

namespace LivreBundle\Interfaces;

interface LieuInterface{
    public function setUser(\UserBundle\Entity\User $user = null);

    public function getUser();
}