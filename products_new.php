<?php
require '../../config/databaseconnect.php';

// Verificar si se está recibiendo una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conectar a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_proyecto";

// Verificar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);
    // Obtener los datos del formulario
    $referencia = $_POST['referencia'];
    $nombre = $_POST['nombre'];
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $exitencia = $_POST['existencia'];
    $cantidadbulto = $_POST['cantbulto'];
    $caracteristica = $_POST['caract'];

    // Verificar si la referencia ya existe
    $sql_check = "SELECT referencia FROM productos WHERE referencia = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $referencia);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si la referencia ya existe, enviar 'reference_exists' como respuesta
        echo 'reference_exists';
    } else {
        // Insertar el nuevo producto
        $sql_insert = "INSERT INTO productos (referencia, nombre,valor, Categoria, Existencia,CantidadBulto,Carac1) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssss", $referencia, $nombre, $valor,$categoria,$exitencia,$cantidadbulto,$caracteristica);

        if ($stmt_insert->execute()) {
            // Si la inserción se realizó con éxito, enviar 'success' como respuesta
            echo 'success';
        } else {
            // Si ocurrió un error, enviar 'error' como respuesta
            echo 'error';
        }

        // Cerrar la declaración de inserción
        $stmt_insert->close();
    }

    // Cerrar la declaración de verificación y la conexión a la base de datos
    $stmt_check->close();
    $conn->close();
    exit; // Salir del script después de enviar la respuesta
}
?>
