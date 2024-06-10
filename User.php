<?php

abstract class User implements AuthInterface
{
    use AuthTrait;
    protected ?string $name = null;

    public function __construct(
        string $login,
        string $password,
        protected int $age,
    ) {
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
