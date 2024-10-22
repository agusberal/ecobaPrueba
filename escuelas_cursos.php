<?php
require_once 'db_connection.php';

function crear_escuela($nombre, $distrito) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO escuelas (nombre, distrito) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $distrito);
    return $stmt->execute();
}

function asociar_docente_escuela($docente_id, $escuela_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET escuela_id = ? WHERE id = ? AND tipo = 'docente'");
    $stmt->bind_param("ii", $escuela_id, $docente_id);
    return $stmt->execute();
}

function crear_curso($nombre, $docente_id, $escuela_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cursos (nombre, docente_id, escuela_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $nombre, $docente_id, $escuela_id);
    return $stmt->execute();
}

function agregar_alumno_curso($alumno_id, $curso_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET escuela_id = (SELECT escuela_id FROM cursos WHERE id = ?) WHERE id = ? AND tipo = 'alumno'");
    $stmt->bind_param("ii", $curso_id, $alumno_id);
    return $stmt->execute();
}

function eliminar_alumno_curso($alumno_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET escuela_id = NULL WHERE id = ? AND tipo = 'alumno'");
    $stmt->bind_param("i", $alumno_id);
    return $stmt->execute();
}