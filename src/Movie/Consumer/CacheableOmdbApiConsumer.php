<?php

namespace App\Movie\Consumer;

use App\Movie\Consumer\Enum\SearchType;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[When('prod')]
#[AsDecorator(OmdbApiConsumer::class, priority: 10)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        protected readonly OmdbApiConsumer $inner,
        protected readonly CacheInterface $cache,
        protected readonly SluggerInterface $slugger,
    ) {
    }

    public function fetch(string $value, SearchType $type): array
    {
        $key = $this->slugger->slug(sprintf("%s-%s", $type->getQueryParam(), $value));

        return $this->cache->get(
            $key,
            function (CacheItemInterface $item) use ($value, $type) {
                $item->expiresAfter(84600);

                return $this->inner->fetch($value, $type);
            }
        );
    }
}
