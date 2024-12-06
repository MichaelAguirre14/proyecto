<?php
require '../../config/databaseconnect.php';
require '../includes/header.php'; 
?>
<?php
// Obtener datos del usuario si se proporciona un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $instanciaConexion = new Conexion();
    $sql = "SELECT * FROM usuarios WHERE idUser = :id";
    $consulta = $instanciaConexion->datos->prepare($sql);
    $consulta->bindParam(':id', $id);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_OBJ);
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
    // Obtener datos del formulario
    $idUsuario = $_POST['id_usuario'];
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $id_estado = isset($_POST['idEstado']) ? $_POST['idEstado'] : '';
    $tipoUsuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : '';
    $nuevaPassword = isset($_POST['nueva_password']) ? $_POST['nueva_password'] : '';

    // Obtener la contraseña original del usuario desde la base de datos
    $instanciaConexion = new Conexion();
    $sql = "SELECT Password FROM usuarios WHERE idUser = :idUsuario";
    $consulta = $instanciaConexion->datos->prepare($sql);
    $consulta->bindParam(':idUsuario', $idUsuario);
    $consulta->execute();
    $fila = $consulta->fetch(PDO::FETCH_OBJ);
    $contrasenaOriginal = $fila->Password;

    // Validar si se proporcionó una nueva contraseña
    if (!empty($nuevaPassword)) {
        // Se proporcionó una nueva contraseña, actualiza
        $contrasena = password_hash($nuevaPassword, PASSWORD_ARGON2ID);
    } else {
        // No se proporcionó una nueva contraseña, usa la contraseña original
        $contrasena = $contrasenaOriginal;
    }
    

    echo "Contraseña a guardar: $contrasena";

    // Actualizar datos en la base de datos
    $consultaEmpleados = "UPDATE usuarios SET NombreRol = :tipo_usuario, User = :usuario, Password = :contrasena, NombreEstado = :id_estado WHERE idUser=:idUsuario";
    $resultadoEmpleados = $instanciaConexion->datos->prepare($consultaEmpleados);
    $resultadoEmpleados->bindParam(':tipo_usuario', $tipoUsuario);
    $resultadoEmpleados->bindParam(':usuario', $usuario);
    $resultadoEmpleados->bindParam(':contrasena', $contrasena);
    $resultadoEmpleados->bindParam(':id_estado', $id_estado);
    $resultadoEmpleados->bindParam(':idUsuario', $idUsuario);
    $resultadoEmpleados->execute();
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Usuario</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Editar Usuario</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <?php
    
    ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-body">
                        <form action="UsuariosEditar.php" method="POST">
                            <h4>ID</h4>
                            <div class="form-group">
                            <input class="form-control" type="text" name="id_usuario" value="<?php echo isset($usuario->idUser) ? $usuario->idUser : $id; ?>" readonly>
                            </div>
                            <p>

                            <h4>USUARIO</h4>
                            <div class="form-group">
                            <input class="form-control" type="text" name="usuario" value="<?php echo isset($usuario->User) ? $usuario->User : ''; ?>"
                                    onkeyup="mayus(this);">
                            </div>
                            <p>

                            <h4>PASSWORD</h4>
                            <div class="form-group">
                                <?php if (empty($contrasena)) { ?>
                                    <input class="form-control" type="password" name="password" value="">
                                <?php } else { ?>
                                    <input type="hidden" name="password_original" value="<?php echo $contrasena; ?>">
                                    <input class="form-control" type="password" name="nueva_password" placeholder="Nueva Contraseña">

                                <?php } ?>
                            </div>
                            <p>

                            <h4>ESTADO</h4>
                            <div class="form-group">
                                <select name="idEstado" id="idEstado">
                                    <option value="Habilitado">Habilitado</option>
                                    <option value="Inhabilitado">Inhabilitado</option>
                                </select>
                            </div>      
                            <p>

                            <h4>TIPO DE USUARIO</h4>
                            <div class="form-group">
                                <select name="tipo_usuario" id="tipo_usuario">
                                <option value="">Seleccione Rol</option>
                                    <option value="Tienda">Tienda</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Mensajero">Mensajero</option>
                                   
                                </select>
                            </div>

                                <input type="submit" class="btn btn-primary btn-block" name="actualizar" value="ACTUALIZAR">
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
function mayus(e) {
    e.value = e.value.toUpperCase().trimEnd();

}
</script>


<?php
require '../includes/footer.php';?>