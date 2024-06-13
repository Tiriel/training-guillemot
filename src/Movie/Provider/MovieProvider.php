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

    public function getOne(string $value, SearchType $type = SearchType::Title): ?Movie
    {
        $movie = $this->searchDb($value, $type);
        if ($movie instanceof Movie) {
            return $movie;
        }

        $data = $this->searchApi($value, $type);
        $movie = $this->buildMovie($data);

        $this->saveMovie($movie);

        return $movie;
    }

    protected function searchDb(string $value, SearchType $type): ?Movie
    {
        return $this->repository->findLikeOmdb($value, $type);
    }

    protected function searchApi(string $value, SearchType $type): array
    {
        return $this->consumer->fetch($value, $type);
    }

    protected function buildMovie(array $data): Movie
    {
        $movie = $this->transformer->transform($data);

        $genres = $this->genreProvider->getFromOmdb($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        return $movie;
    }

    protected function saveMovie(Movie $movie): void
    {
        $this->manager->persist($movie);
        $this->manager->flush();
    }
}
