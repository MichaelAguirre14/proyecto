<?php

// Recibir datos JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

// Conectar a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "disanty";

// Verificar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$successMessage = '';
$errorMessages = [];

foreach ($data as $fila) {
    // Escapar los valores para prevenir inyección SQL
    $referencia = mysqli_real_escape_string($conn, $fila[0]);
    $nombre = mysqli_real_escape_string($conn, $fila[1]);
    $valor = isset($fila[2]) ? $fila[2] : 0;
    $Valor2 = isset($fila[3]) ? $fila[3] : 0;
    $Valor3 = isset($fila[4]) ? $fila[4] : 0;
    $Categoria = isset($fila[5]) ? $fila[5] : 0;
    $Existencia = isset($fila[6]) ? $fila[6] : 0;
    $canitdadbulto = isset($fila[7]) ? $fila[7] : 0;
    $empresa = isset($fila[8]) ? $fila[8] : 0;

    // Verificar si la referencia ya existe en la base de datos
    $sql_existencia = "SELECT COUNT(*) as count FROM productos WHERE referencia = ?";
    $stmt_existencia = $conn->prepare($sql_existencia);
    $stmt_existencia->bind_param("s", $referencia);
    if (!$stmt_existencia->execute()) {
        $errorMessages[] = "Error en la consulta de existencia: " . $stmt_existencia->error;
        continue;
    }
    $result_existencia = $stmt_existencia->get_result();
    $row_existencia = $result_existencia->fetch_assoc();
    $existe_referencia = $row_existencia['count'] > 0;
    $stmt_existencia->close();

    if ($existe_referencia) {
        // Actualizar la cantidad si la referencia ya existe
        $sql_update = "UPDATE productos SET Existencia = ? WHERE referencia = ?";
        $stmt_update = $conn->prepare($sql_update);
        $nueva_existencia = $Existencia;
        $stmt_update->bind_param("ss", $nueva_existencia, $referencia);

        if ($stmt_update->execute()) {
            $successMessage .= "Cantidad actualizada para producto: $referencia\n";
        } else {
            $errorMessages[] = "Error al actualizar cantidad para producto: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        // Insertar un nuevo producto si la referencia no existe
        $sql_insert = "INSERT INTO productos (referencia, nombre, valor, Valor2, Valor3, Categoria, Existencia, CantidadBulto, Empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssdddssss", $referencia, $nombre, $valor, $Valor2, $Valor3, $Categoria, $Existencia, $canitdadbulto, $empresa);

        if ($stmt_insert->execute()) {
            $successMessage .= "Nuevo producto insertado: $referencia\n";
        } else {
            $errorMessages[] = "Error al insertar producto: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

$response = [
    "success" => $successMessage,
    "errors" => $errorMessages
];

echo json_encode($response);

?>
