<?php 
require '../../config/databaseconnect.php';
require '../includes/header.php'; 
$instanciaConexion = new Conexion();
// Consulta para obtener todos los productos
$sql = "SELECT * FROM productos";
$consultasp = $instanciaConexion->datos->prepare($sql);
        $consultasp->execute(); 
?>

<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="container p-12">
                <button class="btn btn-primary" type="button" class="btnagregar" data-toggle="modal" data-target="#modalAgregarProducto">
                    Agregar Producto
                </button>
            </div>
            <div class="card card-outline card-success">
                <div class="card-body">
                    <table id="tablaProductos" class="table table-striped table-bordered table-hover table-responsive">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Referencia</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $contador = 1; ?>
                            <?php while ($producto = $consultasp->fetch(PDO::FETCH_ASSOC)) { 
                                $id = $producto['referencia'];
                               
                                
                            ?>
                                <tr>
                                    <td><?php echo $contador; ?></td>
                                    <td><?php echo $producto['referencia']; ?></td>
                                    <td><?php echo $producto['nombre']; ?></td>
                                    <td><?php echo $producto['Categoria']; ?></td>
                                    
                                    <td>
                                        <!-- Botón de editar con atributo de clase "btn-editar" y atributo de data para pasar el ID del producto -->
                                        <button class="btn btn-primary btn-editar" data-toggle="modal" data-target="#modalEditarProducto" data-id="<?php echo $producto['id']; ?>" data-referencia="<?php echo $producto['referencia']; ?>" data-nombre="<?php echo $producto['nombre']; ?>" data-marca="<?php echo $producto['Categoria']; ?>" data-valor="<?php echo $producto['valor']; ?>" data-valor2="<?php echo $producto['Valor2']; ?>" data-valor3="<?php echo $producto['Valor3']; ?>" data-existencia="<?php echo $producto['Existencia']; ?>" data-cantbulto="<?php echo $producto['CantidadBulto']; ?>" data-carac1="<?php echo $producto['Carac1']; ?>" data-carac2="<?php echo $producto['Carac2']; ?>" data-carac3="<?php echo $producto['Carac3']; ?>">Editar</button>
                                    </td>
                                    <td>
                                        <!-- Botón de eliminar con clase "btn-eliminar" y atributo de data para pasar el ID del producto -->
                                        <button class="btn btn-danger btn-eliminar" data-id="<?php echo $producto['referencia']; ?>">Eliminar</button>
                                    </td>
                                </tr>
                                <?php $contador++; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para editar producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarProducto" enctype="multipart/form-data">
                    <input type="hidden" id="productoId" name="productoId">
                    <input type="hidden" id="referencia" name="referencia">
                 
<!-- Referencia -->
                      <div class="form-group">
                        <label for="referencia">Referencia</label>
                        <input type="text" class="form-control" id="referencia" name="referenciaedit" readonly>
                    </div>
<!-- Nombre -->                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombreedti" required>
                    </div>
<!-- Valor -->                    
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="number" class="form-control" id="valor" name="valoredit" required>
                    </div>
<!-- Valor2 -->                    
                    <div class="form-group">
                        <label for="valor2">Valor2</label>
                        <input type="number" class="form-control" id="valor2" name="valor2edit" required>
                    </div>
<!-- Valor3 -->                    
                    <div class="form-group">
                        <label for="valor3">Valor3</label>
                        <input type="number" class="form-control" id="valor3" name="valor3edit" required>
                    </div>
<!-- Categoria -->                    
                    <div class="form-group">
                        <label for="marca">Categoria</label>
                        <input type="number" class="form-control" id="marca" name="categoriaedit" required>
                    </div>
<!-- Existencia-->                    
                    <div class="form-group">
                        <label for="Existencia">Existencia</label>
                        <input type="number" class="form-control" id="existencia" name="existenciaedit" required>
                    </div>
<!-- Cant. Bulto-->                    
                    <div class="form-group">
                        <label for="cantbulto">Cant. Bulto</label>
                        <input type="number" class="form-control" id="cantbulto" name="cantbultoedit" required>
                    </div>    
<!-- Caracteristica 1 -->                    
                    <div class="form-group">
                        <label for="carac1">Caract. 1</label>
                        <input type="text" class="form-control" id="carac1" name="caract1edti" required>
                    </div>
<!-- Caracteristica 2 -->                    
                    <div class="form-group">
                        <label for="carac2">Caract. 2</label>
                        <input type="text" class="form-control" id="carac2" name="caract2edti" required>
                    </div>
<!-- Caracteristica 3 -->                    
                    <div class="form-group">
                        <label for="carac3">Caract. 3</label>
                        <input type="text" class="form-control" id="carac3" name="caract3edti" required>
                    </div>                    

                </form>
                <button type="button" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Nuevo Producto -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" role="dialog" aria-labelledby="modalAgregarProductoTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarProductoTitle">Agregar Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarProducto" action="products_new.php" method="post">
                    <div class="form-group">
                        <label for="newReferencia">Referencia:</label>
                        <input type="text" class="form-control" id="newReferencia" name="referencia" required>
                    </div>
                    <div class="form-group">
                        <label for="newNombre">Nombre:</label>
                        <input type="text" class="form-control" id="newNombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="newMarca">Valor1:</label>
                        <input type="number" class="form-control" id="valor" name="valor" required>
                    </div>
              
                    <div class="form-group">
                        <label for="newMarca">Categoria:</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newexistencia">Existencia:</label>
                        <input type="text" class="form-control" id="existencia" name="existencia" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newcantbulto">Cant.Bulto:</label>
                        <input type="text" class="form-control" id="cantbulto" name="cantbulto" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newcarac1">Caracterisitca1:</label>
                        <input type="text" class="form-control" id="caract" name="caract" required>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            "paging": true,
            "pageLength": 200,
            "lengthMenu": [200, 500, 1000, 2000],
            "info": true,
            "searching": true,
        });
        
    // Evento click para el botón de editar producto
        document.querySelectorAll('.btn-editar').forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-id');
            var referencia = this.getAttribute('data-referencia');
            var nombre = this.getAttribute('data-nombre');
            var marca = this.getAttribute('data-marca');
            var valor = this.getAttribute('data-valor');
            var valor2= this.getAttribute('data-valor2');
            var valor3 = this.getAttribute('data-valor3');    
            var existencia = this.getAttribute('data-existencia'); 
            var cantbulto = this.getAttribute('data-cantbulto'); 
            var carac1 = this.getAttribute('data-carac1'); 
            var carac2 = this.getAttribute('data-carac2'); 
            var carac3 = this.getAttribute('data-carac3'); 
            

            document.getElementById('productoId').value = productId;
            document.getElementById('referencia').value = referencia;
            document.getElementById('nombre').value = nombre;
            document.getElementById('marca').value = marca;
            document.getElementById('valor').value = valor;
            document.getElementById('valor2').value = valor2;
            document.getElementById('valor3').value = valor3;
            document.getElementById('existencia').value = existencia;
            document.getElementById('cantbulto').value = cantbulto;
            document.getElementById('carac1').value = carac1;
            document.getElementById('carac2').value = carac2;
            document.getElementById('carac3').value = carac3;
           
        });
    });


        // Lógica para enviar el formulario de nuevo producto por AJAX
        $('#formAgregarProducto').submit(function(event) {
            event.preventDefault(); // Evitar el envío estándar del formulario

            var formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        // Mostrar mensaje de SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: '¡Producto creado!',
                            text: 'El producto se ha creado satisfactoriamente.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload(); // Recargar la página
                            }
                        });
                    } else if (response === 'reference_exists') {
                        // Mostrar mensaje de error si la referencia ya existe
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La referencia ya existe. Por favor, usa una referencia diferente.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        // Mostrar mensaje de error genérico si ocurrió algún problema
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al crear el producto. Por favor, inténtalo de nuevo más tarde.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Mostrar mensaje de error genérico si ocurrió algún problema
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });

      // Delegar el evento de clic para los botones de eliminar
$('#tablaProductos').on('click', '.btn-eliminar', function() {
    var productoId = $(this).data('id');
    var confirmarEliminar = confirm('¿Estás seguro de que deseas eliminar este producto?');

    if (confirmarEliminar) {
        $.ajax({
            type: 'POST',
            url: 'product_delete.php',
            data: { id: productoId },
            success: function(response) {
                if (response.trim() === 'success') {  // Asegúrate de que se compara bien
                    Swal.fire({
                        icon: 'success',
                        title: '¡Producto eliminado!',
                        text: 'El producto se eliminó correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            location.reload(); // Recargar la página
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al eliminar el producto. Por favor, inténtalo de nuevo.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }
        });
    }
});




    // Abrir modal con datos del producto
    $('#modalEditarProducto').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var marca = button.data('marca');
        var referencia = button.data('referencia');
        var valor = button.data('valor');
        var valor2 = button.data('valor2');
        var valor3 = button.data('valor3');
        var existencia = button.data('existencia');
        var cantbulto = button.data('cantbulto');
        var carac1 = button.data('carac1');
        var carac2 = button.data('carac2');
        var carac3 = button.data('carac3');


        var modal = $(this);
        modal.find('.modal-body #productoId').val(id);
        modal.find('.modal-body #nombre').val(nombre);
        modal.find('.modal-body #marca').val(marca);
        modal.find('.modal-body #referencia').val(referencia);
        modal.find('.modal-body #valor').val(valor);
        modal.find('.modal-body #valor2').val(valor2);
        modal.find('.modal-body #valor3').val(valor3);
        modal.find('.modal-body #existencia').val(existencia);
        modal.find('.modal-body #cantbulto').val(cantbulto);
        modal.find('.modal-body #caract1').val(carac1);
        modal.find('.modal-body #caract2').val(carac3);
        modal.find('.modal-body #caract3').val(carac3);

        
    });

    $('#btnGuardarCambios').click(function() {
        $('#formEditarProducto').submit();
    });



    $('#formEditarProducto').submit(function(event) {
        event.preventDefault(); // Evitar el envío estándar del formulario

        var formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    // Mostrar mensaje de SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cambios guardados!',
                        text: res.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            location.reload(); // Recargar la página
                        }
                    });
                } else {
                    // Mostrar mensaje de error genérico si ocurrió algún problema
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
                $('#modalEditarProducto').modal('hide');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Mostrar mensaje de error genérico si ocurrió algún problema
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                $('#modalEditarProducto').modal('hide');
            }
        });
    });
    
    $('#formEditarProducto').submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'product_update.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cambios guardados!',
                        text: res.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDismissed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
                $('#modalEditarProducto').modal('hide');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                $('#modalEditarProducto').modal('hide');
            }
        });
    });

    
    });
</script>

<?php include("../includes/footer.php"); ?>
