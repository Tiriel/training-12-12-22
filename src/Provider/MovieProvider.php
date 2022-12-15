<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Entity\User;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Security;

class MovieProvider
{
    private MovieRepository $repository;
    private OmdbApiConsumer $consumer;
    private OmdbMovieTransformer $transformer;
    private GenreProvider $genreProvider;
    private ?SymfonyStyle $io = null;
    private Security $security;

    public function __construct(
        MovieRepository $repository,
        OmdbApiConsumer $consumer,
        OmdbMovieTransformer $transformer,
        GenreProvider $genreProvider,
        Security $security
    ) {
        $this->repository = $repository;
        $this->consumer = $consumer;
        $this->transformer = $transformer;
        $this->genreProvider = $genreProvider;
        $this->security = $security;
    }

    public function setSymfonyStyle(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    public function getMovie(string $type, string $value): Movie
    {
        $this->sendIo('text', 'Searching for base information onOMDb API.');
        $data = $this->consumer->consume($type, $value);

        if (($movie = $this->repository->findOneBy(['title' => $data['Title']])) instanceof Movie) {
            $this->sendIo('note', 'Movie already in Database!');
            return $movie;
        }

        $movie = $this->transformer->transform($data);
        foreach ($this->genreProvider->getGenresFromString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        if ($this->security->getUser() instanceof User) {
            $movie->setCreatedBy($this->security->getUser());
        }

        $this->sendIo('section', 'Saving new movie in database');
        $this->repository->save($movie, true);
        $this->sendIo('text', 'Movie saved');

        return $movie;
    }

    private function sendIo(string $type, string $text)
    {
        if ($this->io instanceof SymfonyStyle && method_exists($this->io, $type)) {
            $this->io->$type($text);
        }
    }
}