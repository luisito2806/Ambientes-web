<?php
session_start();
include_once 'conexion.php';

// Solo con sesión iniciada, igual que el panel
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
$usuarioId = $_SESSION["usuario_id"];

// Cada alumno ve únicamente sus propias calificaciones
$sql = "SELECT id, nombre, materia, calificacion
        FROM calificaciones
        WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<h1>Mis calificaciones</h1>
<p><a href="dashboard.php">Volver al panel</a></p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Materia</th>
        <th>Calificación</th>
    </tr>
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo (int) $fila["id"]; ?></td>
        <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
        <td><?php echo htmlspecialchars($fila["materia"]); ?></td>
        <td><?php echo htmlspecialchars($fila["calificacion"]); ?></td>
    </tr>
<?php } ?>
</table>
