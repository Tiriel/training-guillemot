<?php

namespace App\Movie\Provider;

interface ProviderInterface
{
    public function getOne(string $value): ?object;
}
