<?php
require_once 'db_connection.php';

function actualizarContrasenas() {
    global $conn;
    $usuarios = $conn->query("SELECT id, nombre_usuario FROM usuarios")->fetchAll();
    
    foreach ($usuarios as $usuario) {
        $nuevaContrasena = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = :contrasena WHERE id = :id");
        $stmt->execute([
            'contrasena' => $nuevaContrasena,
            'id' => $usuario['id']
        ]);
        echo "Contraseña actualizada para el usuario: " . $usuario['nombre_usuario'] . "\n";
    }
    echo "Todas las contraseñas han sido actualizadas a '123456'.\n";
}

actualizarContrasenas();