<?php

namespace App\Controller;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieType;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="app_movie_index")
     */
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="app_movie_details")
     */
    public function details(int $id, MovieRepository $repository): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $repository->find($id),
        ]);
    }

    /**
     * @Route("/omdb/{title}", name="app_movie_omdb", methods={"GET"})
     */
    public function omdb(string $title, MovieProvider $provider)
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $provider->getMovie(OmdbApiConsumer::MODE_TITLE, $title),
        ]);
    }

    /**
     * @Route("/new", name="app_movie_new", methods={"GET", "POST"})
     * @Route("/{id}/edit", name="app_movie_edit", methods={"GET", "POST"})
     */
    public function post(Request $request, MovieRepository $repository, ?int $id = null)
    {
        $movie = $id ? $repository->find($id) : new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        if ($request->attributes->get('_route') === 'app_movie_edit') {
            $this->denyAccessUnlessGranted('movie.edit', $movie);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser() instanceof User) {
                $movie->setCreatedBy($this->getUser());
            }
            $repository->save($movie, true);

            return $this->redirectToRoute('app_movie_details', ['id' => $movie->getId()]);
        }

        return $this->render('movie/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
