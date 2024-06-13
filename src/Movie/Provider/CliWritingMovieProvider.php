<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\Enum\SearchType;
use Symfony\Component\Console\Style\SymfonyStyle;

class CliWritingMovieProvider extends MovieProvider
{
    protected ?SymfonyStyle $io = null;

    public function __construct(protected readonly MovieProvider $inner)
    {
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    protected function searchDb(string $value, SearchType $type): ?Movie
    {
        $this->io?->text('Searching in database...');

        $movie = $this->inner->searchDb($value, $type);

        if ($movie instanceof Movie) {
            $this->io?->note('Movie already in database!');
        }

        return $movie;
    }

    protected function searchApi(string $value, SearchType $type): array
    {
        $this->io?->text('Not found. Searching on OMDb API.');

        return $this->inner->searchApi($value, $type);
    }

    protected function buildMovie(array $data): Movie
    {
        $this->io?->note('Found on OMDb! Building Movie.');

        return $this->inner->buildMovie($data);
    }

    protected function saveMovie(Movie $movie): void
    {
        $this->io?->text('Saving movie in database...');
        $this->inner->saveMovie($movie);
    }

}
