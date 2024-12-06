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
    $consultacategoria = "SELECT DISTINCT id ,Nombre_Empresa FROM empresa ORDER BY Nombre_Empresa asc";
    $consultas = $instanciaConexion->datos->prepare($consultacategoria);
    $consultas->execute();
    $consultaObjetos = $consultas->fetchAll(PDO::FETCH_ASSOC); // Utiliza FETCH_ASSOC en lugar de FETCH_OBJ
    ?>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- categoría -->
            <form action="catalogo.php" method="POST" onsubmit="startProgressBar()">

                <div class="form-group">
                    <select name="empresa" id="empresa" class="producto-select">
                        <option value='0'>Seleccionar Empresa</option>
                        <?php
                        foreach ($consultaObjetos as $filacategorias) { // Utiliza foreach para iterar sobre el resultado
                            echo '<option value="' . $filacategorias['id'] . '">' . $filacategorias['Nombre_Empresa'] . '</option>'; // Cierra correctamente la etiqueta option y muestra el valor de la categoría
                        }
                        ?>
                    </select>
                   
                    <input type="submit" name="BUSCAR" class="btn btn-success" value="Buscar">
                </div>
                
            </form>
            
            <?php

            if (isset($_POST['BUSCAR'])) {
                $empresa = $_POST['empresa'];
                $instanciaConexion = new Conexion();
                $consultaempresa = "SELECT *FROM empresa WHERE id = $empresa";
                $consultasempresas = $instanciaConexion->datos->prepare($consultaempresa);
                $consultasempresas->execute();
                $consultaObjetosempresas = $consultasempresas->fetchAll(PDO::FETCH_ASSOC); 
                foreach ($consultaObjetosempresas as $filaempresa) {
                    $nombreEmpresa = $filaempresa['Nombre_Empresa'];
                    }

                    $instanciaConexion = new Conexion();

                    $consultacategorias = "SELECT * FROM categorias WHERE Empresa = '$empresa' AND nombre IS NOT NULL AND nombre != 'NULL'";
                    $consultacategoria = $instanciaConexion->datos->prepare($consultacategorias);
                    $consultacategoria->execute();
                    $consultaObjetoscategoria = $consultacategoria->fetchAll(PDO::FETCH_ASSOC);
           
            ?>
  <form action="catalogoPDF.php" method="POST" autocomplete="off">
    <div class="form-group">
                    <?php  echo 'categoria: ' . $nombreEmpresa;  ?> <br> 
                    <select name="precios" id="precios" class="empresas-select">
                            <option value='valor'>Lista precio 1</option>
                            <option value='Valor2'>Lista precio 2</option>
                            <option value='Valor3'>Lista precio 3</option>
                            <option value='ninguno'>No Mostrar Precios</option>
                        </select> 
                        <input type="int" name="existencias" id="existencia" placeholder="Existencia a mostrar..."  value='1' autocomplete="off">
                <input type="hidden" name="precioSeleccionado" id="precioSeleccionado" value="">

                <!-- Checkbox para mostrar existencias -->
<div class="form-group">
    <input type="checkbox" id="mostrarExistencias" name="mostrarExistencias" onclick="mostrarExistencias(this)">
    <label for="mostrarExistencias">Mostrar Existencias</label>
</div>

<!-- Checkbox para seleccionar/deseleccionar todas las categorías -->
<div class="form-group">
    <input type="checkbox" id="seleccionarTodas" onclick="seleccionarTodasCategorias(this)">
    <label for="seleccionarTodas">Seleccionar todas las lineas</label>
</div>

<?php
            foreach ($consultaObjetoscategoria as $filacategoria) {
                $id = $filacategoria['id_Categoria'];
                $nombecategoria = $filacategoria['nombre'];
                $codigo  = $filacategoria['nombre'];
                echo '<li>';
                echo '<input type="checkbox" name="categoriaSeleccionada[]" value="' . $id . '"> ' . $nombecategoria . '<br>'; // Modificado
                echo '<input type="hidden" name="idempresa_'.$id.'" value="' . $empresa . '">';
                echo '<input type="hidden" name="cot_tip_'.$id.'" value="' . $id . '">';
                echo '<input type="hidden" name="nom_cat_'.$id.'" value="' . $nombecategoria . '">';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
    
    <input type="submit" class="btn btn-info" name="genPDF" value="Generar PDF">
</form>
                                  
                                    <?php
                                    }
                                    ?>
                              

                               
                            </form>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.content -->
        </div><!-- /.content-wrapper -->


        <?php require '../includes/footer.php' ?>
        <script>
    // Función para actualizar el campo oculto "precioSeleccionado"
    function actualizarPrecioSeleccionado() {
        var selectedPrice = document.getElementById("precios").value;
        document.getElementById("precioSeleccionado").value = selectedPrice;
    }

    // Asigna la función al evento onchange de la lista desplegable de precios
    document.getElementById("precios").onchange = actualizarPrecioSeleccionado;

        // Función para mostrar u ocultar existencias según el estado del checkbox
        function mostrarExistencias(checkbox) {
        var existenciaInput = document.getElementById("existencia");
        existenciaInput.style.display = checkbox.checked ? "block" : "none";
    }

    function seleccionarTodasCategorias(checkbox) {
        // Obtiene todas las casillas de verificación de categorías
        var categorias = document.getElementsByName("categoriaSeleccionada[]");

        // Itera a través de ellas y establece su estado según el estado de la casilla "seleccionar todas"
        for (var i = 0; i < categorias.length; i++) {
            categorias[i].checked = checkbox.checked;
        }
    }
</script>