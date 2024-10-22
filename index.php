<?php
session_start();
require_once 'db_connection.php';
require_once 'auth.php';
require_once 'problemas_ambientales.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $descripcion = $_POST['descripcion'];
    $icono = $_POST['icono'];
    
    // Obtener el curso_id del alumno (asumimos que está en la sesión o lo obtenemos de la base de datos)
    $curso_id = obtenerCursoIdDelAlumno($usuario_id);
    
    if (registrar_problema_ambiental($latitud, $longitud, $descripcion, $icono, $usuario_id, $curso_id)) {
        $mensaje = "Problema ambiental registrado con éxito.";
    } else {
        $error = "Error al registrar el problema ambiental.";
    }
}

$problemas = obtener_problemas_ambientales_validados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Problemáticas Ambientales</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input, select, textarea {
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
        }
        button {
            padding: 10px;
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
    <h1>Mapa de Problemáticas Ambientales</h1>
    
    <div id="map"></div>
    
    <?php if ($tipo_usuario === 'alumno'): ?>
    <h2>Registrar nuevo problema ambiental</h2>
    <form method="POST" action="index.php">
        <input type="number" name="latitud" step="any" placeholder="Latitud" required>
        <input type="number" name="longitud" step="any" placeholder="Longitud" required>
        <textarea name="descripcion" placeholder="Descripción del problema" required></textarea>
        <select name="icono" required>
            <option value="water_pollution">Contaminación del agua</option>
            <option value="air_pollution">Contaminación del aire</option>
            <option value="deforestation">Deforestación</option>
            <option value="waste">Residuos</option>
            <!-- Añade más opciones según sea necesario -->
        </select>
        <button type="submit">Registrar problema</button>
    </form>
    <?php endif; ?>

    <?php if (isset($mensaje)): ?>
        <p style="color: green;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <script>
        var map = L.map('map').setView([-36.2048, -60.0369], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var problemas = <?php echo json_encode($problemas); ?>;
        
        problemas.forEach(function(problema) {
            L.marker([problema.latitud, problema.longitud])
                .addTo(map)
                .bindPopup(problema.descripcion);
        });
    </script>

    <p><a href="<?php echo $tipo_usuario; ?>_dashboard.php">Volver al panel de control</a></p>
</body>
</html>