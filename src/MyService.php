<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class MyService implements ServiceInterface
{
    private EntityManagerInterface $manager;
    private string $databaseUrl;

    public function __construct(EntityManagerInterface $manager, string $databaseUrl)
    {
        $this->manager = $manager;
        $this->databaseUrl = $databaseUrl;
    }

    public function setManager(EntityManagerInterface $manager): void
    {
        $this->manager = $manager;
    }

    public function useEntityManager()
    {
        // TODO: Implement useEntityManager() method.
    }
}