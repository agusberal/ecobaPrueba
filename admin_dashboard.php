<?php
session_start();
require_once 'db_connection.php';
require_once 'auth.php';
require_once 'funcionesAdmin.php';

// Verificar si el usuario es un administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$error = '';

// Función para obtener todas las escuelas
function obtener_escuelas() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM escuelas ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener todos los docentes
function obtener_docentes() {
    global $conn;
    $stmt = $conn->query("SELECT u.*, e.nombre as nombre_escuela FROM usuarios u LEFT JOIN escuelas e ON u.escuela_id = e.id WHERE u.tipo = 'docente' ORDER BY u.apellido, u.nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_escuela'])) {
        $nombre = $_POST['nombre'];
        $distrito = $_POST['distrito'];
        if (crear_escuela($nombre, $distrito)) {
            $mensaje = "Escuela creada con éxito.";
        } else {
            $error = "Error al crear la escuela.";
        }
    } elseif (isset($_POST['asociar_docente'])) {
        $docente_id = $_POST['docente_id'];
        $escuela_id = $_POST['escuela_id'];
        if (asociar_docente_escuela($docente_id, $escuela_id)) {
            $mensaje = "Docente asociado a la escuela con éxito.";
        } else {
            $error = "Error al asociar el docente a la escuela.";
        }
    } elseif (isset($_POST['cambiar_estado'])) {
        $usuario_id = $_POST['usuario_id'];
        $nuevo_estado = $_POST['nuevo_estado'];
        if (cambiar_estado_usuario($usuario_id, $nuevo_estado)) {
            $mensaje = "Estado del usuario actualizado con éxito.";
        } else {
            $error = "Error al actualizar el estado del usuario.";
        }
    }
}

$escuelas = obtener_escuelas();
$docentes = obtener_docentes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input, select {
            margin-bottom: 10px;
            padding: 5px;
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
        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Administrador</h1>
        
        <?php if ($mensaje): ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <h2>Crear Nueva Escuela</h2>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre de la escuela" required>
            <input type="text" name="distrito" placeholder="Distrito" required>
            <button type="submit" name="crear_escuela">Crear Escuela</button>
        </form>
        
        <h2>Asociar Docente a Escuela</h2>
        <form method="POST">
            <select name="docente_id" required>
                <option value="">Seleccionar Docente</option>
                <?php foreach ($docentes as $docente): ?>
                    <option value="<?php echo $docente['id']; ?>">
                        <?php echo htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="escuela_id" required>
                <option value="">Seleccionar Escuela</option>
                <?php foreach ($escuelas as $escuela): ?>
                    <option value="<?php echo $escuela['id']; ?>">
                        <?php echo htmlspecialchars($escuela['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="asociar_docente">Asociar Docente</button>
        </form>
        
        <h2>Gestionar Docentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Escuela</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($docente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($docente['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($docente['nombre_escuela'] ?? 'No asignada'); ?></td>
                        <td><?php echo htmlspecialchars($docente['estado']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="usuario_id" value="<?php echo $docente['id']; ?>">
                                <input type="hidden" name="nuevo_estado" value="<?php echo $docente['estado'] === 'activo' ? 'bloqueado' : 'activo'; ?>">
                                <button type="submit" name="cambiar_estado">
                                    <?php echo $docente['estado'] === 'activo' ? 'Bloquear' : 'Desbloquear'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p><a href="logout.php">Cerrar sesión</a></p>
    </div>
</body>
</html>