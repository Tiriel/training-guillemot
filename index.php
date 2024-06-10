<?php

require_once __DIR__.'/AuthInterface.php';
require_once __DIR__.'/AuthTrait.php';
require_once __DIR__.'/User.php';
require_once __DIR__.'/Member.php';
require_once __DIR__.'/AdminLevel.php';
require_once __DIR__.'/Admin.php';

$m1 = new Member('Ben', 'abcd1234', 37);
$m2 = new Member('Tom', 'abcd1234', 25);
$a1 = new Admin('Adminator', 'admin1234', 35, AdminLevel::Admin);

echo 'Members : '.Member::count()."\n";
echo 'Admins : '.Admin::count()."\n";

unset($m2);

echo 'Members : '.Member::count()."\n";
