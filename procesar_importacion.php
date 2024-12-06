<?php

// Recibir datos JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

// Conectar a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_proyecto";

// Verificar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$successMessage = '';
$errorMessages = [];

// Verificar si se han recibido los datos JSON correctamente
if ($data === null) {
    die("No se recibieron datos JSON o el formato es incorrecto.");
}

// Eliminar todos los registros de la tabla productos
$sql_delete = "DELETE FROM productos";
if ($conn->query($sql_delete) === TRUE) {
    $successMessage .= "Todos los productos fueron eliminados exitosamente.\n";
} else {
    $errorMessages[] = "Error al eliminar productos: " . $conn->error;
}

for ($i = 1; $i < count($data); $i++) {
    $fila = $data[$i];
    $referencia = mysqli_real_escape_string($conn, $fila[0]);
    $nombre = mysqli_real_escape_string($conn, $fila[1]);
    $valor = isset($fila[2]) ? $fila[2] : null;
    $valor2 = isset($fila[3]) ? $fila[3] : null;
    $valor3 = isset($fila[4]) ? $fila[4] : null;
    $Categoria = isset($fila[5]) ? $fila[5] : null;
    $Existencia = isset($fila[6]) ? $fila[6] : null;
    $CantBulto = isset($fila[7]) ? $fila[7] : null;
    $Carac1 = isset($fila[8]) ? $fila[8] : null;
    $Carac2 = isset($fila[9]) ? $fila[9] : null;
    $Carac3 = isset($fila[10]) ? $fila[10] : null;
    $Carac4 = isset($fila[11]) ? $fila[11] : null;

    // Verificar que los campos críticos no sean nulos o vacíos
    if (empty($referencia) || empty($nombre)) {
        $errorMessages[] = "Referencia o nombre vacío para la fila: " . json_encode($fila);
        continue;
    }

    // Insertar un nuevo producto
    $sql_insert = "INSERT INTO productos (referencia, nombre, valor,Valor2,Valor3, Categoria, Existencia,CantidadBulto,Carac1,Carac2,Carac3,Carac4) VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        $errorMessages[] = "Error al preparar la consulta: " . $conn->error;
        continue;
    }
    $stmt_insert->bind_param("ssdddsssssss", $referencia, $nombre, $valor,$valor2,$valor3, $Categoria, $Existencia,$CantBulto,$Carac1,$Carac2,$Carac3,$Carac4);

    if ($stmt_insert->execute()) {
        $successMessage .= "Nuevo producto insertado: $referencia\n";
    } else {
        $errorMessages[] = "Error al insertar producto: " . $stmt_insert->error;
    }
    $stmt_insert->close();
}

// Cerrar la conexión a la base de datos
$conn->close();

$response = [
    "success" => $successMessage,
    "errors" => $errorMessages
];

echo json_encode($response);

?>
