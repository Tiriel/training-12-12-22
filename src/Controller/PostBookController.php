<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/new", name="app_book_new", methods={"GET", "POST"})
 * @Route("/book/{id}/edit", name="app_book_edit", methods={"GET", "POST"})
 */
class PostBookController extends AbstractController
{
    public function __invoke(Request $request, BookRepository $repository, ?int $id = null)
    {
        $book = $id ? $repository->find($id) : new Book();

        if ($request->attributes->get('_route') === 'app_book_edit') {
            $this->denyAccessUnlessGranted(BookVoter::EDIT, $book);
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($book, true);

            return $this->redirectToRoute('app_book_details', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}