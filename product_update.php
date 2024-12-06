<?php
require '../../config/databaseconnect.php';

$response = array('status' => 'error', 'message' => 'Hubo un error al procesar la solicitud.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir y sanitizar los datos del formulario
    $referencia = $_POST['referencia'];
    $nombre = $_POST['nombreedti'];
    $valor = $_POST['valoredit'];
    $valor2 = $_POST['valor2edit'];
    $valor3 = $_POST['valor3edit'];
    $categoria = $_POST['categoriaedit'];
    $existencia = $_POST['existenciaedit'];
    $cantidadbulto = $_POST['cantbultoedit'];
    $carac1 = $_POST['caract1edti'];
    $carac2 = $_POST['caract2edti'];
    $carac3 = $_POST['caract3edti'];
    
    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_proyecto";

// Verificar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

    // Usar una consulta preparada para evitar inyección SQL
    $sql = "UPDATE productos 
            SET Nombre = ?, valor = ?, Valor2 = ?, Valor3 = ?, Categoria = ?, 
                Existencia = ?, CantidadBulto = ?, Carac1 = ?, Carac2 = ?, Carac3 = ? 
            WHERE referencia = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Asociar parámetros a la consulta
        $stmt->bind_param('ssssssssssi', $nombre, $valor, $valor2, $valor3, $categoria, $existencia, $cantidadbulto, $carac1, $carac2, $carac3, $referencia);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => 'Producto actualizado correctamente.');
        } else {
            $response['message'] = 'Error al actualizar el producto.';
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $response['message'] = 'Error en la preparación de la consulta.';
    }
}

// Retornar respuesta en formato JSON
echo json_encode($response);
?>
