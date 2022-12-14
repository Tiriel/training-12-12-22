<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private MovieRepository $repository;
    private OmdbApiConsumer $consumer;
    private OmdbMovieTransformer $transformer;
    private GenreProvider $genreProvider;
    private ?SymfonyStyle $io = null;

    public function __construct(
        MovieRepository $repository,
        OmdbApiConsumer $consumer,
        OmdbMovieTransformer $transformer,
        GenreProvider $genreProvider
    ) {
        $this->repository = $repository;
        $this->consumer = $consumer;
        $this->transformer = $transformer;
        $this->genreProvider = $genreProvider;
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