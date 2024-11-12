<?php
session_start();
require_once 'db_connection.php';
require_once 'auth.php';
require_once 'funciones_superior.php';



// Verificar si el usuario es un administrador superior
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador_superior') {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_escuela'])) {
        $nombre = $_POST['nombre'];
        $distrito = $_POST['distrito'];
        if (crear_escuela_superior($nombre, $distrito)) {
            $mensaje = "Escuela creada con éxito.";
        } else {
            $error = "Error al crear la escuela.";
        }
    } elseif (isset($_POST['crear_admin'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];
        $escuela_id = $_POST['escuela_id'];
        if (crear_admin_escuela($nombre, $apellido, $nombre_usuario, $contrasena, $escuela_id)) {
            $mensaje = "Administrador de escuela creado con éxito.";
        } else {
            $error = "Error al crear el administrador de escuela.";
        }
    }
}

$escuelas = obtener_escuelas_superior();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control de Usuario Superior</title>
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
            width: 100%;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
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
        <h1>Panel de Control de Usuario Superior</h1>
        
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
        
        <h2>Crear Administrador de Escuela</h2>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <select name="escuela_id" required>
                <option value="">Seleccionar Escuela</option>
                <?php foreach ($escuelas as $escuela): ?>
                    <option value="<?php echo $escuela['id']; ?>">
                        <?php echo htmlspecialchars($escuela['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="crear_admin">Crear Administrador</button>
        </form>
        
        <h2>Escuelas y Administradores</h2>
        <?php foreach ($escuelas as $escuela): ?>
            <div>
                <h3><?php echo htmlspecialchars($escuela['nombre']); ?></h3>
                <p>Distrito: <?php echo htmlspecialchars($escuela['distrito']); ?></p>
                <h4>Administradores:</h4>
                <ul>
                    <?php 
                    $admins = obtener_admins_escuela($escuela['id']);
                    foreach ($admins as $admin): 
                    ?>
                        <li>
                            <?php echo htmlspecialchars($admin['nombre'] . ' ' . $admin['apellido'] . ' (' . $admin['nombre_usuario'] . ')'); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        
        <p><a href="logout.php">Cerrar sesión</a></p>
    </div>
</body>
</html>