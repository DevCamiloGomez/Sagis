<?php

/**
 * Script para generar el hash de una contraseña
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Contraseña: $password\n";
echo "Hash generado: $hash\n\n";

// También verificar el hash que me diste
$hashExistente = '$2y$10$mSZ9lIZgdecn/3LZuZ1zDeV99IoPIE7lW1Clup975NfxMcTnzh8fG';

echo "Verificando hash existente...\n";
$passwords = ['admin123', 'password', 'admin', '123456', 'password123', 'camilo123', 'ufps123'];

foreach ($passwords as $pass) {
    if (password_verify($pass, $hashExistente)) {
        echo "✓ La contraseña del hash existente es: '$pass'\n";
        break;
    }
}
