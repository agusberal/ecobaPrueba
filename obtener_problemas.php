<?php
require_once 'db_connection.php';
require_once 'problemas_ambientales.php';

header('Content-Type: application/json');
echo json_encode(obtener_problemas_ambientales_validados());