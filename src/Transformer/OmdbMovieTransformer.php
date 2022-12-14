<?php

namespace App\Transformer;

use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (!is_array($value) || !array_key_exists('Title', $value)) {
            throw new \InvalidArgumentException();
        }

        $date = $value['Released'] === 'N/A' ? $value['Year'] : $value['Released'];

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setPlot($value['Plot'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPrice(500)
            ;

        return $movie;
    }

    public function reverseTransform($value)
    {
        throw new \RuntimeException("Not implemented");
    }
}