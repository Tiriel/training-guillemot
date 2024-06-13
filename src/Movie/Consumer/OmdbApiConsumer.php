<?php

namespace App\Movie\Consumer;

use App\Movie\Consumer\Enum\SearchType;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(
        protected readonly HttpClientInterface $client
    ) {
    }

    public function fetch(string $value, SearchType $type): array
    {
        $data = $this->client->request(
            'GET',
            'https://www.omdbapi.com',
            [
                'query' => [
                    'plot' => 'full',
                    'apikey' => '',
                    $type->getQueryParam() => $value,
                ]
            ]
        )->toArray();

        if (\array_key_exists('Error', $data)) {
            if ('Movie not found!' === $data['Error']) {
                throw new NotFoundHttpException();
            }

            throw new HttpException($data['Error']);
        }

        return $data;
    }
}
