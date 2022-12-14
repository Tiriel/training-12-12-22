<?php

namespace App\Transformer;

use App\Entity\Genre;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbGenreTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }

    public function reverseTransform($value)
    {
        throw new \RuntimeException("Not implemented");
    }
}