<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("", name="app_book_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'Book index',
        ]);
    }

    /**
     * @Route("/{id<\d+>?1}", name="app_book_details", methods={"GET"})
     */
    public function details(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'Book details : ' . $id
        ]);
    }
}
