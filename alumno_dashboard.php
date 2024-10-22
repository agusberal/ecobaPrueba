<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'alumno') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Alumno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .logout {
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Alumno</h1>
        <a href="logout.php" class="logout">Cerrar sesión</a>
        <p>Bienvenido, Alumno.</p>
        <p>Desde aquí puedes registrar problemas ambientales y ver tus contribuciones.</p>
        <!-- Aquí puedes agregar más funcionalidades específicas para el alumno -->
        <p><a href="index.php">Ver mapa de problemáticas ambientales</a></p>
    </div>
</body>
</html>
