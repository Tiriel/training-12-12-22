<?php

namespace App\Provider;

use App\Repository\GenreRepository;
use App\Transformer\OmdbGenreTransformer;

class GenreProvider
{
    private OmdbGenreTransformer $transformer;
    private GenreRepository $repository;

    public function __construct(OmdbGenreTransformer $transformer, GenreRepository $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;
    }

    public function getGenresFromString(string $genreNames): \Generator
    {
        $names = explode(', ', $genreNames);

        foreach ($names as $name) {
            yield $this->repository->findOneBy(['name' => $name])
                ?? $this->transformer->transform($name);
        }
    }
}