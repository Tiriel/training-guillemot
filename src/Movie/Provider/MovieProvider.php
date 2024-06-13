<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\Enum\SearchType;
use App\Movie\Consumer\OmdbApiConsumer;
use App\Movie\Transformer\OmdbToMovieTransformer;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider implements ProviderInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly MovieRepository $repository,
        protected readonly OmdbApiConsumer $consumer,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    ) {
    }

    public function getOne(string $value): ?Movie
    {
        $movie = $this->repository->findLikeOmdb($value);
        if ($movie instanceof Movie) {
            return $movie;
        }

        $data = $this->consumer->fetch($value, SearchType::Title);
        $movie = $this->transformer->transform($data);

        $genres = $this->genreProvider->getFromOmdb($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
