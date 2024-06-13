<?php

namespace App\Movie\Transformer;

interface OmdbTransformerInterface
{
    public function transform(mixed $data): object;
}
