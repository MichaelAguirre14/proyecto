<?php

require '../../config/databaseconnect.php';
require '../includes/header.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php

    ?>
    <?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $instanciaConexion = new Conexion();
    $sql = "SELECT * FROM usuarios WHERE idUser = '$id'";
    $consulta = $instanciaConexion->datos->prepare($sql);
    $consulta->execute();
    $consultaObjeto = $consulta->fetchAll(PDO::FETCH_OBJ);

    $i = 0;
    while ($fila = $consulta->fetch(PDO::FETCH_OBJ)) {
        $id_usuario = $fila->idUser;
        $usuarioname = $fila->User;
        $contrasena = $fila->Password;
        $id_estado = $fila->NombreEstado;
        $rol = $fila->NombreRol;
        $idpersona = $fila->IdPersona;
        $i++;
    }
}

    if (isset($_POST['actualizar'])) {
    $idUsuario = $_POST['id_usuario'];
    $usuario = $_POST['usuarioname'];
    $password = $_POST['contrasena'];
    $id_estado = $_POST['id_estado'];
    $roll = $_POST['rol'];
    $idpersona = $_POST['idpersona'];
    
    $consultaEmpleados = "UPDATE usuarios set id_usuario = '$idUsuario', NombreRol = '$roll', User = '$usuario', 
    contrasena =  '$Password',NombreEstado = '$id_estado', WHERE id='$idUsuario'";
    $resultadoEmpleados = sqlsrv_query($conn, $consultaEmpleados);

    if (!$resultadoEmpleados) {
        die("Error");
    }

    header("Location: registroUsuario.php");
    }

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Permisos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Editar Permisos</li>
                    </ol>
                    <h6 class="m-0">  <?php   echo "Usuario: ";?></h6>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
<?php


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM usuarios WHERE idUser = :id";
    $consulta = $instanciaConexion->datos->prepare($sql);
    $consulta->bindParam(':id', $id, PDO::PARAM_INT);
    $consulta->execute();
    $i = 0;
while ($fila = $consulta->fetch(PDO::FETCH_OBJ)) {
    $usuarioname = $fila->User;
 
    $i++;
}
    if ($i === 0) {
        echo "Usuario no encontrado";
        exit();
    }
}
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="card card-body">
                <form action="editar_empleados.php" method="POST">
                    <h4>CAMBIAR PERMISOS DE: </h4>
                    <div class="form-group">
                        <input class="form-control" type="text" name="usuario" value="<?php echo isset($usuarioname) ? $usuarioname : ''; ?>" readonly
                            onkeyup="mayus(this);">
                    </div>
                </form>
                        </div>
                        <div class="col-sm">
    <h5>Asignar Modulos</h5> 
    <!-- Formulario para guardar los módulos activos -->
    <form action="../../modelo/actualizar_modulos.php" method="POST">             
        <ul class="list-group">
            <?php   



$idUsuario = isset($_GET['id']) ? $_GET['id'] : null;

if ($idUsuario !== null) {
    $instanciaConexion = new Conexion();
    $ConsultaSubmodulos = "SELECT id_submodulo FROM apppermisos WHERE id_emp = :id";
    $consultaSub = $instanciaConexion->datos->prepare($ConsultaSubmodulos);
    $consultaSub->bindParam(':id', $idUsuario, PDO::PARAM_INT);
    $consultaSub->execute();
    $consultaSubmodulos = $consultaSub->fetchAll(PDO::FETCH_OBJ);

    // Crear un arreglo con los IDs de los módulos asignados
    $SmodulosAsignados = [];

    foreach ($consultaSubmodulos as $filaModuloUsuario) {
        $SmodulosAsignados[] = $filaModuloUsuario->id_submodulo;
    }
}
$instanciaConexion = new Conexion();

// Consulta para traer los datos de la tabla de appmodulos
$consultam = "SELECT mo.id_modulo_principal, mo.nombre, su.id_submodulo, su.nombres
              FROM appmodulos AS mo
              JOIN appsubmodulos AS su ON su.id_modulo_principal = mo.id_modulo_principal
              ORDER BY mo.id_modulo_principal, su.id_submodulo";

$consultaModulo = $instanciaConexion->datos->prepare($consultam);
$consultaModulo->execute();
$consultaModuloResultados = $consultaModulo->fetchAll(PDO::FETCH_OBJ);
$modulos = []; // Array para agrupar submódulos por módulo principal

foreach ($consultaModuloResultados as $fila) {
    $id_modulo = $fila->id_modulo_principal;
    $nombreM = $fila->nombre;
    $id_sub = $fila->id_submodulo;
    $nombres = $fila->nombres;

    // Almacena los submódulos en el array correspondiente al módulo principal
    $modulos[$id_modulo]['nombre'] = $nombreM;
    $modulos[$id_modulo]['submodulos'][] = [
        'id_sub' => $id_sub,
        'nombres' => $nombres,
    ];
}
                // Verifica si hay algún usuario seleccionado
                if (isset($_GET['id'])) {
                    $id_empleado = $_GET['id'];
                }

                // Genera el HTML a partir de los datos agrupados
                foreach ($modulos as $id_modulo => $modulo) {
                    echo '<li class="list-group-item">';
                    echo '<div>';
                    echo '<div class="modulo">';
                    echo '<div class="modulo-header">';
                    echo '<input type="checkbox" name="modulo[]" value="' . $id_modulo . '" class="seleccionar-modulo">'; // Checkbox para seleccionar el módulo
                    echo '<h4 class="modulo-titulo" onclick="toggleSubmodulos(' . $id_modulo . ')">' . $modulo['nombre'] . '</h4>';
                    echo '</div>';
                    echo '<div class="submodulos" id="submodulos-' . $id_modulo . '" style="display:none;">';

                    foreach ($modulo['submodulos'] as $submodulo) {
                        echo '<div class="submodulo">';
                        echo '<input type="checkbox" name="submodulo[' . $id_modulo . '][]" value="' . $submodulo['id_sub'] . '"';
                        // Comprueba si este submódulo está asignado al usuario y marca la casilla si es necesario
                        if (in_array($submodulo['id_sub'], $SmodulosAsignados)) {
                            echo ' checked';
                        }
                        echo '>';
                        echo '<label>' . $submodulo['nombres'] . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }// Agrega el formulario y el botón "Guardar"
echo '<form action="guardar_permisos.php" method="POST">';
echo '<input type="hidden" name="id_empleado" value="' . $id_empleado . '">';

// Agrega un campo oculto para enviar los módulos activos
foreach ($modulos as $id_modulo => $modulo) {
    echo '<input type="hidden" name="modulosactivos[]" value="' . $id_modulo . '">';
}

echo '<button type="submit" class="btn btn-primary" name="guardar_permisos">Guardar Permisos</button>';
echo '</form>';   

                ?>
        </ul>
    </form>          
</div>         
    
<style>  .modulo {
        display: block; /* Cambia a bloque para mostrar módulos uno debajo del otro */
        margin-bottom: 20px; /* Espaciado entre módulos */
    }

    .submodulo {
        display: block; /* Muestra submódulos uno debajo del otro */
        margin-left: 20px; /* Agrega margen izquierdo para submódulos */
    }
    .modulo-header {
        display: flex; /* Utiliza flexbox para colocar elementos en línea */
        align-items: center; /* Centra verticalmente los elementos */
    }

    .seleccionar-modulo {
        margin-right: 10px; /* Espaciado entre el checkbox y el título del módulo */
    }

    .modulo-titulo {
        cursor: pointer;
        margin: 0; /* Elimina el margen predeterminado del título */
    }

    .modulo {
        display: inline-block;
        vertical-align: top;
        margin-right: 20px;
    }

    /* Otros estilos aquí */

</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moduloCheckboxes = document.querySelectorAll('.seleccionar-modulo');

        moduloCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                const moduloId = this.value;
                const submodulos = document.getElementById('submodulos-' + moduloId).querySelectorAll('input[type="checkbox"]');

                submodulos.forEach(function (submodulo) {
                    submodulo.checked = checkbox.checked;
                });
            });
        });
    });

    function toggleSubmodulos(id_modulo) {
        const submodulos = document.getElementById('submodulos-' + id_modulo);
        submodulos.style.display = submodulos.style.display === 'none' ? 'block' : 'none'; // Alterna la visibilidad
    }
</script>
<?php
require '../includes/footer.php';?>