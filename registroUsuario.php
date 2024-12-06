<?php 
require '../../config/databaseconnect.php';
require '../../modelo/UsuarioModelo.php';
require '../includes/header.php';
$instanciaUsuario = new Usuario();
$objetoRetornado = $instanciaUsuario->mostrarUsuario();
?>

<script>
function mayus(e) {
    e.value = e.value.toUpperCase().trimEnd();
}
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Crear Usuarios</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Crear Usuarios</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3">
           
                <div class="card card-body">
                    <form action="../../controlador/UsuarioControlador.php" method="POST">
                        <input type="hidden" name="action" value="insert">
                        <h4>Nombre</h4>
                        <div class="form-group">
                            <input type="text" name="Name" required="" class="form-control" placeholder="Nombre del Empleado"
                                autofocus onkeyup="mayus(this);">
                        </div>
                        <p>
                        <h4>Usuario</h4>
                        <div class="form-group">
                            <input type="email" name="User" required="" class="form-control" placeholder="Nombre del Usuario"
                                autofocus onkeyup="mayus(this);">
                        </div>
                        <p>
                        <h4>Password</h4>
                        <div class="form-group">
                            <input type="text" name="Password" required="" class="form-control"
                                placeholder="Password del usuario" autofocus>
                        </div>
                        <p>
                        <h4>Estado</h4>
                        <div class="form-group">
                            <select name="Estado">
                                <option value="Habilitado">Habilitado</option>
                                <option value="Inhabilitado">Inhabilitado</option>
                            </select>
                        </div>
                        <h4>Tipo de usuario</h4>
                        <div class="form-group">
                            <select name="Rol">
                                <option>Seleccionar Permiso</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Usuario">Usuario</option>
                                <option value="Bodega">Bodega</option>
                            </select>
                        </div>
                        <p>

                        <input type="submit" class="btn btn-info" name="registrarUsuario" value="Crear Usuario">
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USUARIO</th>
                            <th>ESTADO</th>
                            <th>TIPO USUARIO</th>
                            <th>EDITAR USUARIO</th>
                            <th>EDITAR PERMISOS</th>
              

                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($objetoRetornado as $mostrarCiclo){?>
                            <tr>
                            <td><?php echo $mostrarCiclo->IdUser?></td>
                            <td><?php echo $mostrarCiclo->User?></td>
                            <td><?php echo $mostrarCiclo->NombreEstado?></td>
                            <td><?php echo $mostrarCiclo->NombreRol?></td>
                            <td>
                                <a href="UsuariosEditar.php?id=<?php echo $mostrarCiclo->IdUser?>" class="btn btn-secondary">
                                    <i class="fas fa-user-edit" title="Editar"></i>
                                </a>
                            </td>
                            <td>
                                <a href="editar_permisos.php?id=<?php echo $mostrarCiclo->IdUser?>" class="btn btn-secondary">
                                    <i class="fa-solid fa-list" title="Editar"></i>
                                    
                                </a>
                            </td>
                           
                            </tr>
                        <?php }?>
                    </tbody>

                    
                </table>

                
            </div>

        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
require '../includes/footer.php';?>