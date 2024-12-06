<?php
require '../../config/databaseconnect.php';
require '../includes/header.php'; 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Catálogo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Catálogo</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php
    $instanciaConexion = new Conexion();
    $consultacategoria = "SELECT DISTINCT id, Nombre_Empresa FROM empresa ORDER BY Nombre_Empresa ASC";
    $consultas = $instanciaConexion->datos->prepare($consultacategoria);
    $consultas->execute();
    $consultaObjetos = $consultas->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Formulario de selección de empresa -->
            <form id="empresaForm" action="catalogo.php" method="POST">
                <div class="form-group">
                    <label for="empresa">Seleccionar Empresa</label>
                    <select name="empresa" id="empresa" class="form-control" onchange="mostrarOpcionesBusqueda()">
                        <option value=''>Seleccione una empresa</option>
                        <?php
                        foreach ($consultaObjetos as $filacategorias) {
                            echo '<option value="' . $filacategorias['id'] . '">' . $filacategorias['Nombre_Empresa'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </form>

            <!-- Formulario para seleccionar opción de búsqueda -->
            <div id="opcionesBusqueda" style="display:none;">
                <form id="opcionBusquedaForm" action="catalogo.php" method="POST">
                    <input type="hidden" name="empresa" id="hiddenEmpresa">
                    <div class="form-group">
                        <label for="opcionBusqueda">Seleccionar Opción de Búsqueda</label>
                        <select name="opcionBusqueda" id="opcionBusqueda" class="form-control" onchange="mostrarCamposBusqueda(this.value)" required>
                            <option value=''>Seleccione una opción</option>
                            <option value='categoria'>Por Categoría</option>
                            <option value='busqueda'>Por Búsqueda</option>
                        </select>
                    </div>
                </form>

                <!-- Campos que se muestran u ocultan según la opción seleccionada -->
                <div id="camposBusqueda"></div>
            </div>

            <script>
                function mostrarOpcionesBusqueda() {
                    const empresa = document.getElementById('empresa').value;
                    if (empresa) {
                        document.getElementById('hiddenEmpresa').value = empresa;
                        document.getElementById('opcionesBusqueda').style.display = 'block';
                    } else {
                        document.getElementById('opcionesBusqueda').style.display = 'none';
                        document.getElementById('camposBusqueda').innerHTML = ''; // Limpiar campos de búsqueda
                    }
                }

                function mostrarCamposBusqueda(opcion) {
                    const empresa = document.getElementById('hiddenEmpresa').value;
                    let camposBusqueda = '';

                    if (opcion === 'categoria') {
                        // Realiza la consulta para obtener las categorías cuando se selecciona "Por Categoría"
                        fetch(`get_categories.php?empresa=${empresa}`)
                            .then(response => response.text())
                            .then(data => {
                                camposBusqueda = `
                                    <form action="catalogoPDF.php" method="POST">
                                        <input type="hidden" name="empresa" value="${empresa}">
                                        <input type="hidden" name="categoria" id="categoria" value="categoria">
                                        <div class="form-group">
                                            <?php echo 'Categoría: ' . $consultaObjetos[0]['Nombre_Empresa']; ?> <br> 
                                            <label for="mostrarExistencias">Seleccione lista de Precio</label>
                                        
                                            <select name="precios" id="precios" class="form-control">
                                                <option value='valor'>Lista precio 1</option>
                                                <option value='ninguno'>No Mostrar Precios</option>
                                            </select> 
                                            <label for="mostrarExistencias">Minimo de existencias</label>
                                            <input type="number" name="existencias" id="existencia" placeholder="Existencia a mostrar..." value='1' autocomplete="off" class="form-control">
                                            <input type="hidden" name="precioSeleccionado" id="precioSeleccionado" value="">
                                            <div class="form-group">
                                                <input type="checkbox" id="mostrarExistencias" name="mostrarExistencias" onclick="mostrarExistencias(this)">
                                                <label for="mostrarExistencias">Mostrar Existencias</label>
                                            </div>
                                            <label for="categoria">Categorías</label><br>
                                            ${data}
                                            <input type="checkbox" id="seleccionarTodas" onclick="seleccionarTodasCategorias(this)">
                                            <label for="seleccionarTodas">Seleccionar todas las categorías</label>
                                        </div>
                                        <input type="submit" class="btn btn-info" name="genPDF" value="Generar PDF">
                                    </form>
                                `;
                                document.getElementById('camposBusqueda').innerHTML = camposBusqueda;
                            });
                    } else if (opcion === 'busqueda') {
                        camposBusqueda = `
                            <form action="catalogoPDF.php" method="POST">
                                <input type="hidden" name="empresa" value="${empresa}">
                                <input type="hidden" name="busqueda" id="busqueda" value="busqueda">
                                <div class="form-group">
                                    <?php echo 'EMPRESA SELECCIONADA: ' . $consultaObjetos[0]['Nombre_Empresa']; ?> <br> 
                                    <label for="precios">Seleccione lista de Precio</label>
                                    <select name="precios" id="precios" class="form-control">
                                        <option value='valor'>Lista precio 1</option>
                                        <option value='ninguno'>No Mostrar Precios</option>
                                    </select>
                                    <label for="mostrarExistencias">Minimo de existencias</label>
                                    <input type="number" name="existencias" id="existencia" placeholder="Existencia a mostrar..." value='1' autocomplete="off" class="form-control"> 
                                    <input type="hidden" name="precioSeleccionado" id="precioSeleccionado" value="">
                                    <div class="form-group">
                                        <input type="checkbox" id="mostrarExistencias" name="mostrarExistencias" onclick="mostrarExistencias(this)">
                                        <label for="mostrarExistencias">Mostrar Existencias</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="busquedaPalabra">Buscar por Palabra</label>
                                    <input type="text" name="busquedaPalabra" class="form-control" placeholder="Buscar...">
                                </div>
                                <div class="form-group">
                                    <label for="precioMinimo">Precio Mínimo</label>
                                    <input type="number" name="precioMinimo" class="form-control" placeholder="Precio Mínimo" value="1">
                                </div>
                                <div class="form-group">
                                    <label for="precioMaximo">Precio Máximo</label>
                                    <input type="number" name="precioMaximo" class="form-control" placeholder="Precio Máximo" value="20000">
                                </div>
                                <input type="submit" class="btn btn-info" name="genPDF" value="Generar PDF">
                            </form>
                        `;
                        document.getElementById('camposBusqueda').innerHTML = camposBusqueda;
                    }
                }

                function seleccionarTodasCategorias(checkbox) {
                    var categorias = document.getElementsByName("categoriaSeleccionada[]");
                    for (var i = 0; i < categorias.length; i++) {
                        categorias[i].checked = checkbox.checked;
                    }
                }

                function mostrarExistencias(checkbox) {
                    const existenciaField = document.getElementById('existencia');
                    existenciaField.style.display = checkbox.checked ? 'block' : 'none';
                }
            </script>

        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php require '../includes/footer.php' ?>
