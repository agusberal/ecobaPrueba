<?php

// Función para crear una nueva escuela
function crear_escuela($nombre, $distrito) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO escuelas (nombre, distrito) VALUES (:nombre, :distrito)");
    return $stmt->execute(['nombre' => $nombre, 'distrito' => $distrito]);
}

// Función para asociar un docente a una escuela
function asociar_docente_escuela($docente_id, $escuela_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET escuela_id = :escuela_id WHERE id = :docente_id AND tipo = 'docente'");
    return $stmt->execute(['escuela_id' => $escuela_id, 'docente_id' => $docente_id]);
}

// Función para bloquear/desbloquear un usuario
function cambiar_estado_usuario($usuario_id, $nuevo_estado) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
    return $stmt->execute(['estado' => $nuevo_estado, 'id' => $usuario_id]);
}