<?php

class Member extends User
{
    protected static int $count = 0;

    public function __construct(string $login, string $password, int $age,)
    {
        parent::__construct($login, $password, $age);
        static::$count++;
    }

    public function __destruct()
    {
        static::$count--;
    }

    public static function count(): int
    {
        return static::$count;
    }
}
