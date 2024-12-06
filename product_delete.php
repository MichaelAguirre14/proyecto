<?php
include("database/db.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_proyecto";

// Verificar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validar y sanitizar el valor recibido
if (isset($_POST['id'])) {
    $productoId = mysqli_real_escape_string($conn, $_POST['id']);

    // Preparar la consulta para eliminar el producto de forma segura
    $sqlDeleteProducto = "DELETE FROM productos WHERE referencia = ?";
    $stmt = mysqli_prepare($conn, $sqlDeleteProducto);

    // Vincular el parámetro a la consulta
    mysqli_stmt_bind_param($stmt, "s", $productoId);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        echo 'success';  // Devolver "success" como respuesta
    } else {
        echo 'error';  // Devolver "error" como respuesta
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo 'error'; // Devolver "error" si no se proporcionó un ID válido
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
