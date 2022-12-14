<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;

interface ServiceInterface
{
    public function useEntityManager();

    public function setManager(EntityManagerInterface $manager): void;
}