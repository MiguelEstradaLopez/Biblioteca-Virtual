<?php
session_start();  // Inicia la sesión para verificar si el usuario está logueado

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");  // Si no está logueado, redirige al login
    exit;
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "biblioteca");

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);  // Si hay un error en la conexión, termina el script
}

// CRUD: Crear libro
if (isset($_POST['create'])) {
    $title = $_POST['title'];  // Obtiene el título del libro
    $author = $_POST['author'];  // Obtiene el autor del libro
    $year = $_POST['year'];  // Obtiene el año de publicación

    // Consulta para insertar un nuevo libro
    if (!empty($title) && !empty($author) && !empty($year)) {  // Validación de campos
        $query = "INSERT INTO libros (title, author, year) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);  // Prepara la consulta SQL
        $stmt->bind_param("ssi", $title, $author, $year);  // Vincula los parámetros
        $stmt->execute();  // Ejecuta la consulta para insertar el libro
    }
}

// CRUD: Eliminar libro
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];  // Obtiene el ID del libro a eliminar
    $mysqli->query("DELETE FROM libros WHERE id = $id");  // Elimina el libro de la base de datos
}

// Obtener todos los libros de la base de datos
$result = $mysqli->query("SELECT * FROM libros");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca</title>
    <link rel="stylesheet" href="style.css">  <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Administrar Libros</h2>

        <!-- Formulario para agregar un libro -->
        <form method="POST">
            <input type="text" name="title" placeholder="Título del libro" required />
            <input type="text" name="author" placeholder="Autor" required />
            <input type="text" name="year" placeholder="Año de publicación" required />
            <button type="submit" name="create">Agregar Libro</button>
        </form>

        <!-- Tabla para mostrar los libros -->
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['year']; ?></td>
                        <td><a href="?delete=<?php echo $book['id']; ?>">Eliminar</a></td>  <!-- Enlace para eliminar el libro -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
