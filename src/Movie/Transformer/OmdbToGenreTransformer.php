<?php

namespace App\Movie\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer implements OmdbTransformerInterface
{

    public function transform(mixed $data): Genre
    {
        if (!\is_string($data)) {
            throw new \InvalidArgumentException('$data should be a string');
        }

        if (str_contains($data, ',')) {
            throw new \InvalidArgumentException('Genre string should be exploded.');
        }

        return (new Genre())->setName($data);
    }
}
