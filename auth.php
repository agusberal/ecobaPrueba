<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection.php';
require_once 'funcionesAdmin.php';

function login($nombre_usuario, $contrasena) {
    global $conn;
    if (!$conn) {
        error_log("Error de conexión a la base de datos en auth.php");
        return false;
    }
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute(['nombre_usuario' => $nombre_usuario]);
    $usuario = $stmt->fetch();
    if ($usuario) {
        if (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];
            return true;
        } else {
            error_log("Contraseña incorrecta para el usuario: $nombre_usuario");
        }
    } else {
        error_log("Usuario no encontrado: $nombre_usuario");
    }
    return false;
}

function registrar_usuario($nombre, $apellido, $nombre_usuario, $contrasena, $tipo, $escuela_id = null) {
    global $conn;
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, nombre_usuario, contrasena, tipo, escuela_id) VALUES (:nombre, :apellido, :nombre_usuario, :contrasena, :tipo, :escuela_id)");
    return $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'nombre_usuario' => $nombre_usuario,
        'contrasena' => $hashed_password,
        'tipo' => $tipo,
        'escuela_id' => $escuela_id
    ]);
}

// Función para registrar un administrador superior
function registrar_admin_superior($nombre, $apellido, $nombre_usuario, $contrasena) {
    return registrar_usuario($nombre, $apellido, $nombre_usuario, $contrasena, 'administrador_superior');
}


function cambiar_contrasena($usuario_id, $nueva_contrasena) {
    global $conn;
    $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuarios SET contrasena = :contrasena WHERE id = :id");
    return $stmt->execute([
        'contrasena' => $hashed_password,
        'id' => $usuario_id
    ]);
}

function bloquear_usuario($usuario_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET estado = 'bloqueado' WHERE id = :id");
    return $stmt->execute(['id' => $usuario_id]);
}

function desbloquear_usuario($usuario_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET estado = 'activo' WHERE id = :id");
    return $stmt->execute(['id' => $usuario_id]);
}

function generar_nombre_usuario_aleatorio() {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $nombre_usuario = '';
    for ($i = 0; $i < 8; $i++) {
        $nombre_usuario .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $nombre_usuario;
}
