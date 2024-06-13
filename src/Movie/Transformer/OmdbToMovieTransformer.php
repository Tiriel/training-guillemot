<?php

namespace App\Movie\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer implements OmdbTransformerInterface
{
    private const KEYS = [
        'Title',
        'Plot',
        'Country',
        'Released',
        'Year',
        'Poster',
        'Genre',
        'Rated',
        'imdbID',
    ];

    public function transform(mixed $data): Movie
    {
        if (!\is_array($data)) {
            throw new \InvalidArgumentException('$data should be an array');
        }

        if (0 < \count(\array_diff(self::KEYS, \array_keys($data)))) {
            throw new \InvalidArgumentException('Missing keys from OMDB');
        }

        $date = $data['Released'] === 'N/A' ? '01-01-'.$data['Year'] : $data['Released'];

        return (new Movie())
            ->setTitle($data['Title'])
            ->setPlot($data['Plot'])
            ->setCountry($data['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($data['Poster'])
            ->setRated($date['Rated'])
            ->setImdbId($date['imdbID'])
            ->setPrice(5.0)
        ;
    }
}
