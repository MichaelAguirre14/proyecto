<?php
// Aumentar el límite de memoria
ini_set('memory_limit', '3500M');
require '../../config/databaseconnect.php';
require('../clientside/fpdf/fpdf.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define la clase PDF
class PDF extends FPDF
{
    private $backgroundImage;
    private $portadaimage;
    private $logoMostrado = false;
    
    // Constructor que recibe las imágenes como parámetros
    public function __construct($backgroundImage, $portadaimage)
    {
        parent::__construct();
        $this->backgroundImage = $backgroundImage;
        $this->portadaimage = $portadaimage;
    }

    // Cabecera de página
    function Header()
    {
        if (!$this->logoMostrado) {
            $this->Image($this->portadaimage, 0, 0, $this->w, $this->h);
            $this->logoMostrado = true;
        }

        $this->SetFont('Arial', 'B', 15);
        $this->Ln(10);

        $this->Image($this->backgroundImage, 0, 0, $this->w, $this->h);
        $this->SetFont('Arial', 'B', 15);
        $this->Ln(10);
    }

    // Otros métodos de la clase PDF

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF('fondo1.jpg', 'Logo1.jpg');
$pdf->AliasNbPages();
$pdf->AddPage();

             $portada = 'Logo1.jpg';
            
            if (file_exists($portada)) {
                $pdf->Image($portada, 0, 0, 210, 300);
                $pdf->AddPage();
            }

if (isset($_POST['genPDF']) && isset($_POST['producto_id'])) {
    foreach ($_POST['producto_id'] as $idProducto) {
        $valorModificado = $mysqli->real_escape_string($_POST['valor_' . $idProducto]);


            $consultaItems = "SELECT DISTINCT * 
            FROM productos
                   WHERE referencia = '$idProducto'
                   GROUP BY referencia";

            $result = $mysqli->query($consultaItems);

            if ($result) {
                while ($fila = $result->fetch_assoc()) {
                    $pdf->SetXY(90, 5);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('arial', '', 30);
                    $pdf->Cell(0, 10, $idProducto, 0, 1);

                    $pdf->SetXY(20, 250);
                    $pdf->SetFont('arial', '', 20);
                    $pdf->SetTextColor(0, 0, 0);
                    $ref = substr($fila['nombre'], 0, 35);
                    $pdf->Cell(0, 10, utf8_decode($ref), 0, 1);
                    $pdf->SetFont('arial', 'b', 30);
                    $pdf->SetXY(85, 215);
                    $pdf->Cell(0, 10, '$ ' . number_format($valorModificado, 3, ',', '.'), 0, 1);
                  

                    $imagen = $fila['image_id'];

                    if (!empty($fila['file_data'])) {
                        $imageData = $fila['file_data'];
                        $tempImagePath = $imagen . '.jpg';
                        file_put_contents($tempImagePath, $imageData);

                        $pdf->Image($tempImagePath, 30, 30, 150, 150);
 $pdf->AddPage();
                        unlink($tempImagePath);
                    } else {
                        $pdf->Text($x, $y, "Imagen no disponible");
                        
                    }

                   
                }
                
            } else {
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Error en la consulta: ' . $mysqli->error, 0, 1);
            }
        }
    

    $pdf->Output('Catalogo.pdf', 'D');
}
?>

