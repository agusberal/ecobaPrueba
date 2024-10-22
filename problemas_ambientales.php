<?php
require_once 'db_connection.php';

function registrar_problema_ambiental($latitud, $longitud, $descripcion, $icono, $alumno_id, $curso_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO problemas_ambientales (latitud, longitud, descripcion, icono, alumno_id, curso_id) VALUES (:latitud, :longitud, :descripcion, :icono, :alumno_id, :curso_id)");
    return $stmt->execute([
        'latitud' => $latitud,
        'longitud' => $longitud,
        'descripcion' => $descripcion,
        'icono' => $icono,
        'alumno_id' => $alumno_id,
        'curso_id' => $curso_id
    ]);
}

function validar_problema_ambiental($problema_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE problemas_ambientales SET estado = 'validado' WHERE id = :id");
    return $stmt->execute(['id' => $problema_id]);
}

function agregar_comentario($problema_id, $docente_id, $comentario) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comentarios (problema_id, docente_id, comentario) VALUES (:problema_id, :docente_id, :comentario)");
    return $stmt->execute([
        'problema_id' => $problema_id,
        'docente_id' => $docente_id,
        'comentario' => $comentario
    ]);
}

function obtener_problemas_ambientales_validados() {
    global $conn;
    $stmt = $conn->prepare("SELECT pa.*, u.nombre_usuario, e.nombre as escuela FROM problemas_ambientales pa JOIN usuarios u ON pa.alumno_id = u.id JOIN escuelas e ON u.escuela_id = e.id WHERE pa.estado = 'validado'");
    $stmt->execute();
    return $stmt->fetchAll();
}

function obtenerCursoIdDelAlumno($alumno_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT escuela_id FROM usuarios WHERE id = :alumno_id");
    $stmt->execute(['alumno_id' => $alumno_id]);
    $result = $stmt->fetch();
    return $result ? $result['escuela_id'] : null;
}

function obtener_problemas_pendientes() {
    global $conn;
    $stmt = $conn->prepare("SELECT pa.*, u.nombre_usuario FROM problemas_ambientales pa JOIN usuarios u ON pa.alumno_id = u.id WHERE pa.estado = 'pendiente' ORDER BY pa.fecha_creacion DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}