<?php
require('../clientside/fpdf/fpdf.php');
require '../../config/databaseconnect.php';
ini_set('memory_limit', '5000M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Realizar la consulta para traer los datos del encabezado 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores seleccionados en el arreglo categoriaSeleccionada[]
    $categoriasSeleccionadas = isset($_POST['categoriaSeleccionada']) ? $_POST['categoriaSeleccionada'] : [];

    // Verifica que haya al menos una categoría seleccionada
    if (count($categoriasSeleccionadas) > 0) {
        // Crear el objeto PDF
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage(); // portrait para orientación vertical
        $pdf->SetMargins(30, 30, 30); // Establece las márgenes izquierda, arriba y derecha
        $pdf->SetAutoPageBreak(true, 1); // Habilita el salto automático de página con un margen inferior de 20 mm
   
        $instanciaConexion = new Conexion();
        // Obtén el valor de existencias desde el formulario
        $existencias = isset($_POST['existencias']) ? (int)$_POST['existencias'] : 0;

        // Recorrer el arreglo de categorías seleccionadas
        foreach ($categoriasSeleccionadas as $categoriaId) {
            // Obtiene los valores de idempresa y cot_tip correspondientes a la categoría seleccionada
            $idEmpresa = $_POST["idempresa_$categoriaId"];
            $codigo = $_POST["cot_tip_$categoriaId"];
            $mostrarExistencias = isset($_POST['mostrarExistencias']) ? true : false;
            $precioSeleccionado = $_POST['precios'];

            $consultacategorias = "SELECT *FROM categorias WHERE id_Categoria = $codigo AND Empresa = $idEmpresa";
            $consultacategoria = $instanciaConexion->datos->prepare($consultacategorias);
            $consultacategoria->execute();
            $consultaObjetoscategoria = $consultacategoria->fetchAll(PDO::FETCH_ASSOC);
            foreach ($consultaObjetoscategoria as $filacategoria) {
                $nombecategoria = $filacategoria['nombre'];
            }
           

            $consultaproductos = "SELECT productos.referencia,productos.nombre, productos.Existencia,productos.CantidadBulto,productos.valor,productos.Valor2,productos.Valor3
                                        FROM productos 
                                            JOIN categorias ON productos.Categoria = categorias.id_Categoria
                                                WHERE productos.Categoria = '$codigo' AND categorias.id_Categoria = '$codigo'  AND productos.Existencia >$existencias AND productos.Empresa ='$idEmpresa'AND categorias.Empresa = '$idEmpresa'";

        $consultasp = $instanciaConexion->datos->prepare($consultaproductos);
        $consultasp->execute(); 


            // Verifica si se encontraron productos
            if ($consultasp->rowCount() > 0) {
                
                $portada = '../../img/portada.jpeg';
                if (file_exists($portada)) {
                    $pdf->Image($portada, 0, 0, 210, 270);
                    $pdf->SetFont('times', 'B', 30);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY(70, 260);
                    $pdf->Cell(0, 10,$nombecategoria, 0, 1);
                }

                while ($fila = $consultasp->fetch(PDO::FETCH_ASSOC)) {
                    $codigo = $fila['referencia'];
                    $nombres = $fila['nombre'];
                    $existencia = $fila['Existencia'];  
                    $cantidadbulto = $fila['CantidadBulto'];
                    
                    $background = '../../img/fondo.png';
                  $imagen = '../../ARCHIVOS/'.$idEmpresa.'/' . trim($codigo) . '.JPG';


        // Si hay una imagen, agrégala al PDF
        if (file_exists($imagen)) {
            $pdf->AddPage();
            $pdf->Image($background, 0, 0, 210, 300);
            
           $pdf->Image($imagen, 23, 58, 165,150);}
                    

           if ($precioSeleccionado == 'valor') {
            $precios = $fila['valor'];
        } elseif ($precioSeleccionado == 'ninguno') {
            $precios = '';
        }
        
        if ($precios !== '') {
            // Asegúrate de que $precios sea un número antes de continuar
            $precios = (float)$precios;
        
            if ($precios < 100000) {
                // Si el precio es menor a 100,000
                $precioformateado1 = $precios * 1.2;
        
                $multiplo = 1000; 
                $precioformateado1 = ceil($precioformateado1 / $multiplo) * $multiplo;
                
                $preciosStr = str_replace(['.', ','], '', (string)$precios);
                $precioformateado1Str = str_replace(['.', ','], '', (string)$precioformateado1);
                
                // Asegúrate de que las cadenas tengan al menos dos dígitos
                $preciosStr = str_pad($preciosStr, 2, "0", STR_PAD_LEFT);
                $precioformateado1Str = str_pad($precioformateado1Str, 2, "0", STR_PAD_LEFT);
                
                // Concatenar los primeros dos dígitos de cada cadena
                $nuevoPrecioformateadoStr = $preciosStr[0] . $precioformateado1Str[0] . $preciosStr[1] . $precioformateado1Str[1];
                $nuevoPrecioformateado = (float)$nuevoPrecioformateadoStr;
            } else {
                // Si el precio es mayor o igual a 100,000
                $preciosStr = str_replace(['.', ','], '', (string)$precios);
        
                // Asegúrate de que la cadena tenga al menos tres dígitos
                $preciosStr = str_pad($preciosStr, 3, "0", STR_PAD_LEFT);
        
                // Tomar solo los primeros tres dígitos
                $nuevoPrecioformateadoStr = $preciosStr[0] . $preciosStr[1] . $preciosStr[2];
                $nuevoPrecioformateado = (float)$nuevoPrecioformateadoStr;
            }
        
            if ($precioSeleccionado != 'ninguno') {
                $pdf->SetFont('Arial', '', 40);
                $pdf->SetTextColor(225, 225, 225); // Blanco
                $pdf->SetXY(74, 220);
                $pdf->Cell(0, 10, 'COD'. $nuevoPrecioformateado, 0, 1);
            }
        }
        
                      //$pdf->Image($imagen, 15, 55, 180, 153, '', '', '', '', 30);$pdf->SetFont('Arial', 'b', 22);
$pdf->SetTextColor(0, 0, 0); // Establece el color de texto a negro
$pdf->SetFont('Arial', 'b', 20);
// Establece las coordenadas para la MultiCell (X, Y)
$pdf->SetXY(40, 6);

// Ajusta el ancho y la altura de la MultiCell según sea necesario
$cellWidth = 130; // Ancho de la celda
$cellHeight = 5; // Altura de la celda

// Agrega la MultiCell
$pdf->MultiCell($cellWidth, $cellHeight, $nombres, 0, 'C');

                    $pdf->SetFont('Arial', '', 38);
                    $pdf->SetTextColor(225, 225, 225);
                    $pdf->SetXY(75,285);
                    $pdf->Cell(0, 10, $codigo, 0, 1);


                    $pdf->SetFont('Arial', '', 30);
                    $pdf->SetTextColor(225, 225, 225);
                    $pdf->SetXY(156, 250);
                    if ($mostrarExistencias) {
                        $pdf->Cell(0, 10, $existencia, 0, 1);
                    }

                    $pdf->SetFont('Arial', '', 30);
                    $pdf->SetTextColor(225, 225, 225);
                    $pdf->SetXY(28, 250);
                    $pdf->Cell(0, 10, $cantidadbulto, 0, 1);
                }
            } else {
                // No se encontraron productos para esta categoría
            }
        }

        // Solo genera el PDF si se agregó al menos un producto en alguna página
        if ($pdf->PageNo() > 1) {
            $fechaActual = date('d-m-Y');
            $nombreArchivo = $nombecategoria . '_' . $fechaActual . '.pdf';
            $pdf->Output($nombreArchivo, 'I');
        } else {
            echo "No se encontraron productos para las categorías seleccionadas.";
        }
    }
}
?>
