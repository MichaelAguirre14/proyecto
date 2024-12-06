<?php

// Recibir datos JSON del cliente
$data = json_decode(file_get_contents('php://input'), true);

// Conectar a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erifer";

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
    $valor = isset($fila[2]) ? $fila[2] : null;
    $Valor2 = isset($fila[3]) ? $fila[3] : null;
    $Valor3 = isset($fila[4]) ? $fila[4] : null;
    $Categoria = isset($fila[5]) ? $fila[5] : null;
    $Existencia = isset($fila[6]) ? $fila[6] : null;
    $canitdadbulto = isset($fila[7]) ? $fila[7] : null;
    $empresa = isset($fila[8]) ? $fila[8] : null;

    // Verificar si la referencia ya existe en la base de datos
    $sql_existencia = "SELECT COUNT(*) as count FROM productos WHERE referencia = ? AND Empresa = ?";
    $stmt_existencia = $conn->prepare($sql_existencia);
    $stmt_existencia->bind_param("ss", $referencia,$empresa);
    if (!$stmt_existencia->execute()) {
        $errorMessages[] = "Error en la consulta de existencia: " . $stmt_existencia->error;
        continue;
    }
    $result_existencia = $stmt_existencia->get_result();
    $row_existencia = $result_existencia->fetch_assoc();
    $existe_referencia = $row_existencia['count'] > 0;
    $stmt_existencia->close();

    if ($existe_referencia) {
        // Construir la consulta de actualización
        $sql_update = "UPDATE productos SET ";

        // Array para almacenar las asignaciones de campos
        $update_values = array();

        // Actualizar los campos que tienen valores no nulos y no vacíos
        if ($nombre !== null && $nombre !== '') {
            $update_values[] = "nombre = '$nombre'";
        }
        if ($valor !== null) {
            $update_values[] = "valor = $valor";
        }
        if ($Valor2 !== null) {
            $update_values[] = "Valor2 = $Valor2";
        }
        if ($Valor3 !== null) {
            $update_values[] = "Valor3 = $Valor3";
        }
        if ($Categoria !== null) {
            $update_values[] = "Categoria = $Categoria";
        }
        if ($Existencia !== null) {
            $update_values[] = "Existencia = $Existencia";
        }
        if ($canitdadbulto !== null) {
            $update_values[] = "CantidadBulto = $canitdadbulto";
        }
        if ($empresa !== null) {
            $update_values[] = "Empresa = $empresa";
        }

        // Construir la lista de asignaciones
        $sql_update .= implode(", ", $update_values);

        // Agregar la condición WHERE
        $sql_update .= " WHERE referencia = ?";
        
        // Preparar y ejecutar la consulta de actualización
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $referencia);

        if ($stmt_update->execute()) {
            $successMessage .= "Datos actualizados para producto: $referencia\n";
        } else {
            $errorMessages[] = "Error al actualizar datos para producto: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        // Insertar un nuevo producto si la referencia no existe
        $sql_insert = "INSERT INTO productos (referencia, nombre, valor, Valor2, Valor3, Categoria, Existencia, CantidadBulto, Empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssddddsss", $referencia, $nombre, $valor, $Valor2, $Valor3, $Categoria, $Existencia, $canitdadbulto, $empresa);

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
