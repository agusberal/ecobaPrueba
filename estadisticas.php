<?php
require_once 'db_connection.php';

function generar_estadisticas() {
    global $conn;
    $estadisticas = [];

    // Total de problemas ambientales registrados
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM problemas_ambientales");
    $stmt->execute();
    $estadisticas['total_problemas'] = $stmt->get_result()->fetch_assoc()['total'];

    // Problemas ambientales por distrito
    $stmt = $conn->prepare("SELECT e.distrito, COUNT(*) as total FROM problemas_ambientales pa JOIN cursos c ON pa.curso_id = c.id JOIN escuelas e ON c.escuela_id = e.id GROUP BY e.distrito");
    $stmt->execute();
    $estadisticas['problemas_por_distrito'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Problemas ambientales por tipo de icono
    $stmt = $conn->prepare("SELECT icono, COUNT(*) as total FROM problemas_ambientales GROUP BY icono");
    $stmt->execute();
    $estadisticas['problemas_por_icono'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $estadisticas;
}