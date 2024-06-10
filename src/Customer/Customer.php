<?php

namespace App\Customer;

use App\Auth\Interface\AuthInterface;
use App\Auth\Trait\AuthTrait;

class Customer implements AuthInterface
{
    use AuthTrait;
}
