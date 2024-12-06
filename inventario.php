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
                    <h1 class="m-0">Inventario</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Inventario</li>
                        
                    </ol>
                    
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.17.2/dist/xlsx.full.min.js"></script>
  <button onclick="exportToExcel()">Descargar Plantilla</button>
    </div>
    <!-- /.content-header -->
<!-- Main content -->
<div class="content">
        <div class="container-fluid">
			<div class="container p-12">
				<div class="card card-outline card-success">

                <body>
    <input type="file" id="archivo_excel" accept=".xls, .xlsx" />
    <button onclick="importarDatos()">Importar</button>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="tu_script.js"></script>
</body>
				</div>
			</div>
		</div>
	</div>		
    <script>
    function exportToExcel() {
      console.log("Función exportToExcel() ejecutada");

      // Crear datos para Excel
      let data = [
       ["referencia", "nombre", "valor", "valor2", "valor3", "categoria(num)", "existencia","Cant. Bulto", "Caracteristica 1", "Caracteristica 2", "Caracteristica 3", "Caracteristica 4"]
       
      ];

      // Crear un libro de trabajo de Excel
      let wb = XLSX.utils.book_new();
      let ws = XLSX.utils.aoa_to_sheet(data);

      // Agregar hoja al libro de trabajo
      XLSX.utils.book_append_sheet(wb, ws, "Hoja1");

      // Convertir el libro de trabajo a blob
      let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });

      // Convertir el blob en un objeto Blob
      let blob = new Blob([wbout], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});

      // Crear un enlace para descargar
      let url = window.URL.createObjectURL(blob);
      let link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'PlantillaInventario.xlsx');
      document.body.appendChild(link);
      link.click();

      // Limpiar el objeto URL
      setTimeout(function() {
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
      }, 0);
    }
  </script>


    <script>


function importarDatos() {
    console.log('Función importarDatos() ejecutada');
    var input = document.getElementById('archivo_excel');
    var archivo = input.files[0];

    if (archivo) {
        var lector = new FileReader();

        lector.onload = function(e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array' });

            var primeraHoja = workbook.Sheets[workbook.SheetNames[0]];
            var datos = XLSX.utils.sheet_to_json(primeraHoja, { header: 1 });

            enviarDatosAlServidor(datos);
        };

        lector.onprogress = function(event) {
            if (event.lengthComputable) {
                var percentLoaded = Math.round((event.loaded / event.total) * 100);
                Swal.getContent().querySelector('.progress-bar').style.width = percentLoaded + '%';
                Swal.getContent().querySelector('.progress-bar').innerText = percentLoaded + '%';
            }
        };

        lector.readAsArrayBuffer(archivo);

        // Mostrar una barra de progreso mientras se carga
        Swal.fire({
            title: 'Importando datos...',
            html: '<div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div>',
            onBeforeOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Seleccione un archivo Excel',
        });
    }
}

function enviarDatosAlServidor(datos) {
    var datosJSON = JSON.stringify(datos);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'procesar_importacion.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            var percentLoaded = Math.round((event.loaded / event.total) * 100);
            Swal.getContent().querySelector('.progress-bar').style.width = percentLoaded + '%';
            Swal.getContent().querySelector('.progress-bar').innerText = percentLoaded + '%';
        }
    };

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            Swal.close(); // Cerrar la barra de progreso

            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Importación correcta',
                }).then(() => {
                    // Redirigir a la página de productos después de hacer clic en "OK"
                    window.location.href = 'inventario.php?page=productos'; // Ajusta la URL según tu estructura de archivos
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la importación.',
                });
            }
        }
    };

    xhr.send(datosJSON);
}


function mostrarMensajeExito() {
    Swal.fire({
        icon: 'success',
        title: 'Importación correcta',
        text: 'Todos los registros se importaron correctamente.',
    });
}

        </script>
        </div>	
        
<?php
require '../includes/footer.php';
?>