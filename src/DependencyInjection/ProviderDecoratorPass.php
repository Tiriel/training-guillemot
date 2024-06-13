<?php

namespace App\DependencyInjection;

use App\Movie\Provider\CliWritingMovieProvider;
use App\Movie\Provider\MovieProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProviderDecoratorPass implements CompilerPassInterface
{

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ('cli' === \PHP_SAPI) {
            $container->register(CliWritingMovieProvider::class, CliWritingMovieProvider::class)
                ->setDecoratedService(MovieProvider::class)
                ->setAutowired(true)
                ->setAutoconfigured(true)
            ;
        }
    }
}
