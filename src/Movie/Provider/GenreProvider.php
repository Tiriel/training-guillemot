<?php

namespace App\Movie\Provider;

use App\Entity\Genre;
use App\Movie\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider implements ProviderInterface
{
    public function __construct(
        protected readonly GenreRepository $repository,
        protected readonly OmdbToGenreTransformer $transformer,
    ) {
    }

    public function getOne(string $value): ?Genre
    {
        return $this->repository->findOneBy(['name' => $value])
            ?? $this->transformer->transform($value);
    }

    public function getFromOmdb(string $genres): iterable
    {
        foreach (explode(', ', $genres) as $name) {
            yield $this->getOne($name);
        }
    }
}
