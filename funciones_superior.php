<?php
require_once 'db_connection.php';

function crear_escuela_superior($nombre, $distrito) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO escuelas (nombre, distrito) VALUES (:nombre, :distrito)");
    return $stmt->execute(['nombre' => $nombre, 'distrito' => $distrito]);
}

function crear_admin_escuela($nombre, $apellido, $nombre_usuario, $contrasena, $escuela_id) {
    global $conn;
    $conn->beginTransaction();

    try {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, nombre_usuario, contrasena, tipo) VALUES (:nombre, :apellido, :nombre_usuario, :contrasena, 'administrador')");
        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'nombre_usuario' => $nombre_usuario,
            'contrasena' => $hashed_password
        ]);
        $usuario_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO administradores_escuela (usuario_id, escuela_id) VALUES (:usuario_id, :escuela_id)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'escuela_id' => $escuela_id
        ]);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error al crear administrador de escuela: " . $e->getMessage());
        return false;
    }
}

function obtener_escuelas_superior() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM escuelas ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_admins_escuela($escuela_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT u.id, u.nombre, u.apellido, u.nombre_usuario 
        FROM usuarios u 
        JOIN administradores_escuela ae ON u.id = ae.usuario_id 
        WHERE ae.escuela_id = :escuela_id
    ");
    $stmt->execute(['escuela_id' => $escuela_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}