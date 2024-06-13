<?php

namespace App\Movie\Consumer;

use App\Movie\Consumer\Enum\SearchType;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('dev')]
#[When('test')]
#[AsDecorator(OmdbApiConsumer::class, priority: 5)]
class TraceableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        protected readonly OmdbApiConsumer $inner,
        protected readonly LoggerInterface $logger,
    ) {
    }

    public function fetch(string $value, SearchType $type): array
    {
        $this->logger->info('New query for OMDb Movie data', [
            'searchType' => $type->getQueryParam(),
            'value' => $value,
        ]);

        return $this->inner->fetch($value, $type);
    }
}
