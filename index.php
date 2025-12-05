<?php
session_start();  // Inicia una nueva sesión o retoma la sesión actual. Esto es necesario para gestionar la sesión de usuario.

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");  // Si ya está logueado, redirige automáticamente al dashboard.
    exit;
}

// Si se envía el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];  // Obtiene el nombre de usuario ingresado en el formulario
    $password = $_POST['password'];  // Obtiene la contraseña ingresada en el formulario

    // Conexión a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "biblioteca");
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);  // Si hay un error en la conexión, termina el script y muestra un mensaje de error.
    }

    // Consulta para verificar el usuario y la contraseña
    $query = "SELECT * FROM usuarios WHERE username = ? AND password = MD5(?)";  // Usamos MD5 para encriptar la contraseña
    $stmt = $mysqli->prepare($query);  // Prepara la consulta para evitar inyecciones SQL
    $stmt->bind_param('ss', $username, $password);  // Vincula las variables a la consulta
    $stmt->execute();  // Ejecuta la consulta
    $result = $stmt->get_result();  // Obtiene el resultado de la consulta

    // Si se encuentra un usuario
    if ($result->num_rows > 0) {
        $_SESSION['logged_in'] = true;  // Establece la sesión para indicar que el usuario está logueado
        header("Location: dashboard.php");  // Redirige al usuario al dashboard
        exit;
    } else {
        $error_message = "Nombre de usuario o contraseña incorrectos";  // Si no se encuentra el usuario, muestra un mensaje de error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca</title>
    <link rel="stylesheet" href="style.css">  <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Bienvenido a la Biblioteca</h2>
        <form method="POST">  <!-- El formulario envía los datos mediante el método POST -->
            <input type="text" name="username" placeholder="Nombre de usuario" required />  <!-- Campo para el nombre de usuario -->
            <input type="password" name="password" placeholder="Contraseña" required />  <!-- Campo para la contraseña -->
            <button type="submit">Iniciar Sesión</button>  <!-- Botón para enviar el formulario -->
        </form>
        <?php if (isset($error_message)) { ?>  <!-- Si existe un error, lo muestra -->
            <p id="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
