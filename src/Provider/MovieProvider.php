<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;

class MovieProvider
{
    private MovieRepository $repository;
    private OmdbApiConsumer $consumer;
    private OmdbMovieTransformer $transformer;
    private GenreProvider $genreProvider;

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

    public function getMovie(string $type, string $value): Movie
    {
        $data = $this->consumer->consume($type, $value);

        if (($movie = $this->repository->findOneBy(['title' => $data['Title']])) instanceof Movie) {
            return $movie;
        }

        $movie = $this->transformer->transform($data);
        foreach ($this->genreProvider->getGenresFromString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->repository->save($movie, true);

        return $movie;
    }
}