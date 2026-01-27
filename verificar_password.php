<?php

/**
 * Script para verificar qué contraseña corresponde a un hash bcrypt
 */

$hash = '$2y$10$mSZ9lIZgdecn/3LZuZ1zDeV99IoPIE7lW1Clup975NfxMcTnzh8fG';

// Contraseñas comunes a probar
$passwords = [
    'admin123',
    'password',
    'admin',
    '123456',
    'password123',
    'admin1234',
    'camilo123',
    'ufps123',
    'sagis123',
];

echo "Verificando hash: $hash\n\n";

foreach ($passwords as $password) {
    if (password_verify($password, $hash)) {
        echo "✓ CONTRASEÑA ENCONTRADA: '$password'\n";
        exit(0);
    } else {
        echo "✗ '$password' - No coincide\n";
    }
}

echo "\n⚠ No se encontró coincidencia con las contraseñas comunes probadas.\n";
echo "La contraseña puede ser otra o el hash puede estar corrupto.\n";
