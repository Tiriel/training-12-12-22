<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @IsGranted("ROLE_TOTO")
     * @Route("/hello/{name<[a-zA-Z- ]+>?World}", name="app_hello_index", methods={"GET"})
     */
    public function index(string $name, string $sfVersion): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name . " - Sf version : " . $sfVersion,
        ]);
    }
}
