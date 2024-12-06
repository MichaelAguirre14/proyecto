<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../config/databaseconnect.php';

$instanciaConexion = new Conexion(); 
$conexion = $instanciaConexion->datos;

// Verificar que el formulario fue enviado y contiene datos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['categorias']) && isset($_POST['etiquetas'])) {
    $categorias = $_POST['categorias'];
    $etiquetas = $_POST['etiquetas'];

    // Iniciar la transacción
    $conexion->beginTransaction();

    try {
        // Preparar la consulta SQL para verificar la existencia del id_Categoria
        $verificarStmt = $conexion->prepare("SELECT COUNT(*) FROM categorias WHERE id_Categoria = :etiqueta");

        // Preparar la consulta SQL para insertar una nueva categoría
        $insertarStmt = $conexion->prepare("INSERT INTO categorias (id_Categoria, nombre, Empresa) VALUES (:etiqueta, :nombre, '1')");

        // Preparar la consulta SQL para actualizar una categoría existente
        $actualizarStmt = $conexion->prepare("UPDATE categorias SET nombre = :nombre WHERE id_Categoria = :etiqueta");

        for ($i = 0; $i < count($categorias); $i++) {
            // Limpiar y validar cada categoría y etiqueta
            $categoria = trim($categorias[$i]);
            $etiqueta = trim($etiquetas[$i]);

            // Verificar si el id_Categoria ya existe
            $verificarStmt->bindParam(':etiqueta', $etiqueta);
            $verificarStmt->execute();
            $existe = $verificarStmt->fetchColumn();

            if ($existe) {
                // Si el id_Categoria ya existe, actualizar
                $actualizarStmt->bindParam(':etiqueta', $etiqueta);
                $actualizarStmt->bindParam(':nombre', $categoria);
                $actualizarStmt->execute();
            } else {
                // Si el id_Categoria no existe, insertar
                $insertarStmt->bindParam(':etiqueta', $etiqueta);
                $insertarStmt->bindParam(':nombre', $categoria);
                $insertarStmt->execute();
            }
        }

        // Confirmar la transacción
        $conexion->commit();

        // Redirigir a la pantalla anterior con un mensaje de éxito
        header('Location: CrearCategorias.php?mensaje=success');
        exit();
        
    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $conexion->rollBack();

        // Redirigir a la pantalla anterior con un mensaje de error
        header('Location: CrearCategorias.php?mensaje=error');
        exit();
    }

    // Cerrar la conexión
    $instanciaConexion->cerrarconexion();
} else {
    echo "No se recibieron categorías para guardar.";
}
