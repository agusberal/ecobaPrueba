<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'docente') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Docente</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            color: #333;
            text-decoration: none;
        }
        a:hover {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Docente</h1>
        <a href="logout.php" class="logout">Cerrar sesión</a>
        <p>Bienvenido, Docente.</p>
        <ul>
            <li><a href="validar_problemas.php">Validar problemas ambientales</a></li>
            <li><a href="index.php">Ver mapa de problemáticas ambientales</a></li>
            <li><a href="#">Gestionar cursos</a></li>
            <li><a href="#">Ver progreso de alumnos</a></li>
        </ul>
    </div>
</body>
</html>