<?php
require '../../config/databaseconnect.php';
require '../../modelo/UsuarioModelo.php';
require '../includes/header.php';

$instanciaUsuario = new Usuario();
$categorias = $instanciaUsuario->mostrarCategorias(); // Obtén las categorías desde la base de datos

// Obtener el siguiente número de categoría
$contador = 1; // Valor inicial
if (!empty($categorias)) {
    $ultimoId = max(array_map(fn($categoria) => (int) $categoria->id, $categorias));
    $contador = $ultimoId + 1; // El siguiente número es el mayor id + 1
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<div class="content-wrapper">
    <script>
    let contador = <?php echo $contador; ?>;

    function agregarCategoria() {
        const contenedor = document.getElementById('categorias');
        const numeroCategoria = contador.toString().padStart(3, '0');
        
        // Crear un nuevo div para cada categoría
        const nuevaCategoria = document.createElement('div');
        nuevaCategoria.className = 'categoria';
        
        nuevaCategoria.innerHTML = `
            <label for="categoria_${contador}">${numeroCategoria}:</label>
            <input type="hidden" name="etiquetas[]" value="${numeroCategoria}">
            <input type="text" id="categoria_${contador}" name="categorias[]" placeholder="Nombre de la categoría" required>
        `;

        contenedor.appendChild(nuevaCategoria);
        contador++;
    }
    </script>

    <h1>Crear Categorías</h1>

    <form action="AñadirCategoria.php" method="POST">
        <div id="categorias">
            <?php
            // Mostrar las categorías existentes
            foreach ($categorias as $categoria) {
                $numeroCategoria = str_pad($categoria->id, 3, '0', STR_PAD_LEFT);

                // Mostrar la categoría
                echo "<div class='categoria'>
                        <label for='categoria_{$categoria->id}'>{$numeroCategoria}:</label>
                        <input type='hidden' name='etiquetas[]' value='{$numeroCategoria}'>
                        <input type='text' id='categoria_{$categoria->id}' name='categorias[]' value='{$categoria->nombre}' placeholder='Nombre de la categoría' required>
                    </div>";
            }
            ?>
        </div>

        <button type="button" class="btn btn-primary" onclick="agregarCategoria()">Añadir otra categoría</button>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>

    <?php
    // Mostrar mensajes usando SweetAlert
    if (isset($_GET['mensaje'])) {
        $mensaje = $_GET['mensaje'];
        if ($mensaje == 'success') {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Categorías guardadas correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                });
            </script>";
        } elseif ($mensaje == 'error') {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al guardar las categorías.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                });
            </script>";
        }
    }
    ?>
</div>
<!-- /.content-wrapper -->

<?php
require '../includes/footer.php';
?>
