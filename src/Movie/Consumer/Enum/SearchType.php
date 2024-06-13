<?php

namespace App\Movie\Consumer\Enum;

enum SearchType
{
    case Id;
    case Title;

    public function getQueryParam(): string
    {
        return match ($this) {
            self::Id => 'i',
            self::Title => 't',
        };
    }
}
