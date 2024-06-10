<?php

use App\Auth\Exception\AuthException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\User\Admin;
use App\User\Enum\AdminLevel;
use App\User\Member;

require_once __DIR__.'/vendor/autoload.php';

$m1 = new Member('Ben', 'abcd1234', 37);
$m2 = new Member('Tom', 'abcd1234', 25);
$a1 = new Admin('Adminator', 'admin1234', 35, AdminLevel::Admin);

$countM =  'Members : '.Member::count()."\n";
$countA =  'Admins : '.Admin::count()."\n";

unset($m2);

$countM =  'Members : '.Member::count()."\n";

try {
    $a1->auth('Adminator', 'abcd1234');
    $auth = "Authenticated\n";
} catch (AuthException $e) {
    $auth = $e->getMessage()."\n";
}

$twig = new Environment(new FilesystemLoader([__DIR__.'/templates']));

echo $twig->render('index.html.twig', [
    'members' => $countM,
    'admins' => $countA,
    'auth' => $auth,
]);
