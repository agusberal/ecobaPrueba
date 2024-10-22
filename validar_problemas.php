<?php
session_start();
require_once 'db_connection.php';
require_once 'auth.php';
require_once 'problemas_ambientales.php';

// Verificar si el usuario es un docente
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'docente') {
    header('Location: login.php');
    exit();
}

$docente_id = $_SESSION['usuario_id'];
$mensaje = '';
$error = '';

// Procesar la validación si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['validar'])) {
        $problema_id = $_POST['problema_id'];
        if (validar_problema_ambiental($problema_id)) {
            $mensaje = "Problema ambiental validado con éxito.";
        } else {
            $error = "Error al validar el problema ambiental.";
        }
    } elseif (isset($_POST['comentar'])) {
        $problema_id = $_POST['problema_id'];
        $comentario = $_POST['comentario'];
        if (agregar_comentario($problema_id, $docente_id, $comentario)) {
            $mensaje = "Comentario agregado con éxito.";
        } else {
            $error = "Error al agregar el comentario.";
        }
    }
}

// Obtener problemas pendientes de validación
$problemas_pendientes = obtener_problemas_pendientes();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Problemas Ambientales</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .mensaje {
            color: green;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Validar Problemas Ambientales</h1>
        
        <?php if ($mensaje): ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <?php if (empty($problemas_pendientes)): ?>
            <p>No hay problemas ambientales pendientes de validación.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Alumno</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($problemas_pendientes as $problema): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($problema['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($problema['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($problema['fecha_creacion']); ?></td>
                            <td>
                                <form method="POST" action="validar_problemas.php">
                                    <input type="hidden" name="problema_id" value="<?php echo $problema['id']; ?>">
                                    <button type="submit" name="validar">Validar</button>
                                </form>
                                <form method="POST" action="validar_problemas.php">
                                    <input type="hidden" name="problema_id" value="<?php echo $problema['id']; ?>">
                                    <textarea name="comentario" placeholder="Agregar comentario" required></textarea>
                                    <button type="submit" name="comentar">Comentar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <p><a href="docente_dashboard.php">Volver al panel de control</a></p>
    </div>
</body>
</html>