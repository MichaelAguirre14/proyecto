<?php
// Aumentar el límite de memoria
ini_set('memory_limit', '500M'); // Puedes ajustar el valor según tus necesidades
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../clientside/fpdf/fpdf.php");
require '../../config/databaseconnect.php';

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
    }
    // Pie de página
    function Footer()
    {
        // Pie de página del PDF
    }
}


if (isset($_POST['producto_id'])) {
    foreach ($_POST['producto_id'] as $idProducto) {
        // Crear el objeto PDF
        $pdf = new PDF();
        $pdf->AliasNbPages();

        // Obtén el valor de existencias desde el formulario
      //  $existencias = isset($_POST['existencias']) ? (int)$_POST['existencias'] : 0;

        // Recorrer el arreglo de categorías seleccionadas
       // foreach ($categoriasSeleccionadas as $categoriaId) {
            // Obtiene los valores de idempresa y cot_tip correspondientes a la categoría seleccionada
         //   $idEmpresa = $_POST["idempresa_$categoriaId"];
            //$codigo = $_POST["cot_tip_$c"];
            $mostrarExistencias = isset($_POST['mostrarExistencias']) ? true : false;
            $precioSeleccionado = $_POST['precios'];
            $existencias = $_POST['existencias'];

            // Consulta para obtener el nombre de la categoría
           // $consultanombre = "SELECT nombre_categoria 
            //FROM AppCategoriaProductos
            //WHERE (COD_TIP = '$codigo') AND (empresa_id = '$idEmpresa')";
            //$ejecnombre = sqlsrv_query($conn, $consultanombre);

            //if ($fila3 = sqlsrv_fetch_array($ejecnombre)) {
            //    $nombreCategoria = $fila3['nombre_categoria'];
           // }

            // Utilizar el valor seleccionado en la consulta SQL
            $consultaE = "SELECT *
                            FROM productos
                            WHERE (referancia = '$idProducto') AND (Existencia > $existencias)";


            if (!$mostrarExistencias) {
                $consultaE .= " AND (EXISTENCIA > $existencias)";
            }

            $consultaE .= " AND (referancia NOT LIKE '%0000003%')";
            $consultaE .= " ORDER BY nombre ASC"; // Agregar esta línea para ordenar por nom_ref1 de forma ascendente

            $ejec = sqlsrv_query($conn, $consultaE);
            $pdf->AddPage();

                $portada = '../../img/Logo1.jpg';

            if (file_exists($portada)) {
                $pdf->Image($portada, 0, 0, 210, 300);
                // Agregar el nombre de la categoría en la portada
                //$pdf->SetFont('times', 'B', 30);
                //$pdf->SetTextColor(225, 225, 225); // Establece el color de texto a negro
                //$pdf->SetXY(85, 230); // Ajusta la posición según tus necesidades
                //$pdf->Cell(0, 10, utf8_decode($nombreCategoria), 0, 1); // Mostrar el nombre de la categoría
                $pdf->AddPage();
            }
            if ($ejec) {
                $total_productos = sqlsrv_num_rows($ejec);
                $contador = 0;

                while ($fila = sqlsrv_fetch_array($ejec)) {
                    if ($contador > 0) {
                        $pdf->AddPage();
                    }
                    $empresaNombre = $fila['nombre'];
                    $codigo = $fila['refencia'];

                        $background = '../../img/fondo1.jpg';
                  
                    if (file_exists($background)) {
                        $pdf->Image($background, 0, 0, 210, 300);
                    }

                    $imagen = 'ARCHIVOS/FOTOS/' . $empresaNombre . '/' . trim($codigo) . '.jpg';

                    // Si hay una imagen, agrégala al PDF
                    if (file_exists($imagen)) {
                       $pdf->Image($imagen, 15, 55, 180, 153);
                       //$pdf->Image($imagen, 15, 55, 180, 153, '', '', '', '', 30);

                    }

                    $cadena = $fila['nombre'];
                    $posicion_espacio = strpos($cadena, " ");
                    $primera_parte = substr($cadena, 0, $posicion_espacio); // Contiene "ABC1"

                    $pdf->SetFont('Arial', '', 38);
                    $pdf->SetTextColor(0, 0, 0); // Establece el color de texto a negro
                    $pdf->SetXY(79, 235);

                    if ($precioSeleccionado == 'valor') {
                        $precios = $fila['valor'];
                    } elseif ($precioSeleccionado == 'Valor2') {
                        $precios = $fila['Valor2'];
                    } elseif ($precioSeleccionado == 'Valor3') {
                        $precios = $fila['Valor3'];
                    } elseif ($precioSeleccionado == 'ninguno') {
                        $precios = ''; // Cambié $precios1 a $precios
                    }

                    if ($precioSeleccionado != 'ninguno') {
                        $pdf->Cell(0, 10, '$' . number_format($precios, 0, ',', '.'), 0, 1);
                    }

                    $pdf->SetFont('Arial', '', 38);
                    $pdf->SetTextColor(0, 0, 0); // Establece el color de texto a negro
                    $pdf->SetXY(150, 18);
                    $pdf->Cell(0, 10, $primera_parte, 0, 1);

                    $pdf->SetFont('Arial', '', 14);
                    $pdf->SetTextColor(225, 225, 225); // Establece el color de texto a negro
                    $pdf->SetXY(14, 215);
                    $pdf->Cell(0, 10, utf8_decode($fila['referencia']), 0, 1,'L');

                    $pdf->SetFont('Arial', '', 30);
                    $pdf->SetTextColor(225, 225, 225); // Establece el color de texto a negro
                    $pdf->SetXY(178, 228);
                    if ($mostrarExistencias) {
                        $pdf->Cell(0, 10, number_format($fila['Existencia'], 0, ',', '.'), 0, 1);
                    }

                   // $pdf->SetFont('Arial', '', 30);
                    //$pdf->SetTextColor(225, 225, 225); // Establece el color de texto a negro
                    //$pdf->SetXY(15, 228);
                    //$pdf->Cell(0, 10, number_format($fila['canti_bult'], 0), 0, 1);

                    $contador++;
                }
            } else {
                // Manejar errores de consulta aquí
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Error en la consulta: ' . sqlsrv_errors(), 0, 1);
            }
        }
        // Solo genera el PDF si se agregó al menos un producto en alguna página
        if ($pdf->PageNo() > 1) {
            $codigo = $_POST["referencia$categoriaId"];
            $fechaActual = date('d-m-Y');
            $nombreArchivo = $empresaNombre . '_' . $codigo . '_' . $fechaActual . '.pdf';
            $pdf->Output($nombreArchivo, 'D');
        } else {
            echo "No se seleccionaron categorías.";
        }
    }



?>
