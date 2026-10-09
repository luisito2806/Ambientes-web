<?php
session_start();
include_once "conexion.php";

// Si se recibe la petición de salir, destruimos la sesión aquí mismo
if (isset($_GET['salir'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
$usuarioId = $_SESSION["usuario_id"];

// Obtener datos del alumno[cite: 3]
$sql = "SELECT id, nombre, email, created_at
        FROM usuarios
        WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Obtener calificaciones vinculadas[cite: 3]
$sql = "SELECT calificaciones.materia, calificaciones.calificacion
        FROM calificaciones
        WHERE calificaciones.usuario_id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$calificaciones = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Alumno — SuperHi School</title>
    <style>
        :root {
            --color-chalk-blue: #f0f6ff;
            --color-paper-white: #ffffff;
            --color-ink-black: #111118;
            --color-electric-iris: #2727e6;
            --color-frost-blue: #e1edff;
        }
        body {
            background-color: var(--color-chalk-blue);
            font-family: ui-sans-serif, system-ui, sans-serif;
            color: var(--color-ink-black);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            background: var(--color-paper-white);
            border: 1px solid var(--color-frost-blue);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 2px 0 0 var(--color-ink-black);
        }
        h1 { font-size: 42px; margin-top: 0; letter-spacing: -0.03em; }
        h2 { font-size: 24px; border-bottom: 2px solid var(--color-chalk-blue); padding-bottom: 8px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--color-frost-blue);
        }
        th { font-weight: 600; color: var(--color-electric-iris); }
        .btn-logout {
            display: inline-block;
            background: var(--color-ink-black);
            color: var(--color-paper-white);
            padding: 10px 20px;
            border-radius: 48px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 0 0 var(--color-electric-iris);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Hola, <?php echo htmlspecialchars($usuario["nombre"]); ?> 👋</h1>
            <p><strong>Número de Control / ID:</strong> <?php echo $usuario["id"]; ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario["email"]); ?></p>
            <p><strong>Fecha de registro:</strong> <?php echo $usuario["created_at"]; ?></p>
        </div>

        <div class="card">
            <h2>Mis Calificaciones</h2>
            <table>
                <tr>
                    <th>Materia</th>
                    <th>Calificación</th>
                </tr>
                <?php while ($fila = $calificaciones->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila["materia"]); ?></td>
                    <td><strong><?php echo htmlspecialchars($fila["calificacion"]); ?></strong></td>
                </tr>
                <?php } ?>
            </table>
            <br>
            <!-- El enlace apunta al mismo archivo mandando la variable salir=true -->
            <a href="dashboard.php?salir=true" class="btn-logout">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>