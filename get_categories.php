<?php
require '../../config/databaseconnect.php';

$empresa = isset($_GET['empresa']) ? intval($_GET['empresa']) : 0;

$instanciaConexion = new Conexion();

$consultacategorias = "SELECT * FROM categorias WHERE Empresa = :empresa AND nombre IS NOT NULL AND nombre != '' AND nombre != 'NULL'";
$consultacategoria = $instanciaConexion->datos->prepare($consultacategorias);
$consultacategoria->bindParam(':empresa', $empresa, PDO::PARAM_INT);
$consultacategoria->execute();
$consultaObjetoscategoria = $consultacategoria->fetchAll(PDO::FETCH_ASSOC);

foreach ($consultaObjetoscategoria as $filacategoria) {
    $id = $filacategoria['id_Categoria'];
    $nombrecategoria = $filacategoria['nombre'];
    echo '<li>';
    echo '<input type="checkbox" name="categoriaSeleccionada[]" value="' . $id . '"> ' . $nombrecategoria . '<br>';
    echo '<input type="hidden" name="idempresa_' . $id . '" value="' . $empresa . '">';
    echo '<input type="hidden" name="cot_tip_' . $id . '" value="' . $id . '">';
    echo '<input type="hidden" name="nom_cat_' . $id . '" value="' . $nombrecategoria . '">';
    echo '</li>';
}
?>
