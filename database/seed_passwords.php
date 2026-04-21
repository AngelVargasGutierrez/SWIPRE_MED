<?php
// Genera hashes correctos para los usuarios de prueba
// Ejecutar: php seed_passwords.php
$passwords = [
    'admin123'    => password_hash('admin123',    PASSWORD_BCRYPT),
    'farmacia123' => password_hash('farmacia123', PASSWORD_BCRYPT),
    'jefatura123' => password_hash('jefatura123', PASSWORD_BCRYPT),
];

echo "-- Ejecuta este SQL para actualizar contraseñas:\n\n";
$users = ['admin' => $passwords['admin123'], 'farmacia' => $passwords['farmacia123'], 'jefatura' => $passwords['jefatura123']];
foreach ($users as $user => $hash) {
    echo "UPDATE usuarios SET password = '$hash' WHERE username = '$user';\n";
}
