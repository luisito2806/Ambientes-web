<?php
include_once "conexion.php";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) trim($_POST["id"]);
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Verificar si el ID o el email ya existen
    $sqlCheck = "SELECT id FROM usuarios WHERE id = ? OR email = ?";
    $stmtCheck = $conexion->prepare($sqlCheck);
    $stmtCheck->bind_param("is", $id, $email);
    $stmtCheck->execute();
    $resultadoCheck = $stmtCheck->get_result();

    if ($resultadoCheck->num_rows > 0) {
        $mensaje = "Error: El número de control o el correo ya están registrados.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Guardamos directamente email_verificado en 1 para que sea automático
        $sql = "INSERT INTO usuarios (id, nombre, email, password, email_verificado) VALUES (?, ?, ?, ?, 1)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isss", $id, $nombre, $email, $passwordHash);

        if ($stmt->execute()) {
            $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
        } else {
            $mensaje = "No se pudo registrar el usuario.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro — SuperHi School</title>
    <style>
        :root {
            --color-chalk-blue: #f0f6ff;
            --color-paper-white: #ffffff;
            --color-ink-black: #111118;
            --color-electric-iris: #2727e6;
            --color-frost-blue: #e1edff;
            --color-marker-red: #ff4141;
        }
        body {
            background-color: var(--color-chalk-blue);
            font-family: ui-sans-serif, system-ui, sans-serif;
            color: var(--color-ink-black);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: var(--color-paper-white);
            border: 1px solid var(--color-frost-blue);
            border-radius: 24px;
            padding: 32px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 0 0 var(--color-ink-black);
        }
        h1 { font-size: 35px; margin-top: 0; letter-spacing: -0.03em; }
        label { display: block; font-size: 16px; margin-bottom: 8px; font-weight: 500; }
        input {
            width: 100%;
            background: var(--color-chalk-blue);
            border: 1px solid var(--color-frost-blue);
            border-radius: 5000px;
            padding: 12px 20px;
            margin-bottom: 20px;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
        }
        input:focus { border-color: var(--color-electric-iris); }
        button {
            background: var(--color-electric-iris);
            color: var(--color-paper-white);
            border: none;
            border-radius: 48px;
            padding: 12px 24px;
            font-size: 18px;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 4px 0 0 var(--color-ink-black);
            font-weight: bold;
        }
        p { font-size: 15px; text-align: center; color: var(--color-electric-iris); margin-top: 15px; }
        .footer-link { text-align: center; margin-top: 20px; }
        a { color: var(--color-electric-iris); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Crea tu cuenta</h1>
        <form method="POST">
            <label>Número de control</label>
            <input type="number" name="id" min="1" required>
            
            <label>Nombre</label>
            <input type="text" name="nombre" required>
            
            <label>Email</label>
            <input type="email" name="email" required>
            
            <label>Contraseña</label>
            <input type="password" name="password" required>
            
            <button type="submit">Registrarme →</button>
        </form>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
        <div class="footer-link">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>