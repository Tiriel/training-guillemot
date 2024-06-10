<?php

namespace App\User;

use App\Auth\Interface\AuthInterface;
use App\Auth\Trait\AuthTrait;

abstract class User implements AuthInterface
{
    use AuthTrait;

    protected ?string $name = null;

    public function __construct(
        string $login,
        string $password,
        protected int $age,
    ) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): User
    {
        $this->name = $name;

        return $this;
    }
}
