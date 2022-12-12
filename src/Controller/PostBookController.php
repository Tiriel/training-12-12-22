<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/new", name="app_book_new", methods={"GET", "POST"})
 */
class PostBookController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'Book new',
        ]);
    }
}