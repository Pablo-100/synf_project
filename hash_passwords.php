<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

$factory = new PasswordHasherFactory([
    'common' => ['algorithm' => 'bcrypt'],
]);

$passwordHasher = $factory->getPasswordHasher('common');

$password = 'admin123';
$hash = $passwordHasher->hash($password);

echo "Mot de passe : $password\n";
echo "Hash : $hash\n";
echo "\n";
echo "SQL à exécuter dans phpMyAdmin :\n\n";
echo "UPDATE user SET password = '$hash' WHERE email = 'admin@example.com';\n";
echo "UPDATE user SET password = '$hash' WHERE email = 'user@example.com';\n";
