<?php
session_start();
include("../model/MySQL.php");
$conexion = new MySQL();
$pdo = $conexion->conectar();
$sql = "SELECT novedades.idNovedades, novedades.fechayHora, novedades.tipoNovedad, novedades.descripcion, novedades.estadoIntervencion, novedades.recomendaciones, equipos.numeroSerie, equipos.nombre as 'nombreEquipo', equipos.ultimoMantenimiento, administradores.nombre as 'nombreAdmin', personal_mantenimiento.idPersonal_Mantenimiento, personal_mantenimiento.nombre as 'nombrePersonal', personal_mantenimiento.apellido FROM novedades INNER JOIN equipos ON novedades.idEquipo=equipos.idEquipos INNER JOIN administradores ON administradores.idAdministradores = novedades.Administradores_idAdministradores INNER JOIN personal_mantenimiento ON personal_mantenimiento.idPersonal_Mantenimiento = novedades.Personal_Mantenimiento_idPersonal_Mantenimiento;";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fila = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- Theme style -->

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if (isset($_SESSION['error'])) {
    ?>
    <script>
    let msj = '<?php echo $_SESSION['error'] ?>'
    Swal.fire(
        "Error",
        msj,
        'error'
    )
    </script>
    <?php
        unset($_SESSION['error']);
    }
    ?>

    <?php
    if (isset($_SESSION['felicitaciones'])) {
    ?>
    <script>
    let msj = '<?php echo $_SESSION['felicitaciones'] ?>'
    Swal.fire(
        "Accion Realizada",
        msj,
        'success'
    )
    </script>
    <?php
        unset($_SESSION['felicitaciones']);
    }
    ?>
    <script>
    function eliminarEquipo(id) {
        Swal.fire({
            title: `¿ Deseas Eliminar El Equipo #${id} ?`,
            showDenyButton: true,
            confirmButtonText: "Eliminar",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                ejecutarControladorEliminar(id)
            }
        });
    }

    function ejecutarControladorEliminar(id) {
        // Realizar una solicitud AJAX a tu controlador de eliminación en PHP
        $.ajax({
            url: '../controller/eliminarEquipo.php', // Ajusta la ruta a tu controlador PHP
            method: 'POST', // O el método que estés utilizando en tu controlador PHP
            data: {
                id: id
            }, // Puedes enviar datos adicionales según tus necesidades
            success: function(response) {
                // Manejar la respuesta del servidor después de la eliminación
                Swal.fire("Eliminado Correctamente!", "", "success");
                setTimeout(() => {
                    window.location.reload()
                }, 1500);
            },
            error: function(error) {
                // Manejar errores, si es necesario
                Swal.fire("Error al eliminar", "", "error");
            }
        });
    }

    function select(id, e) {
        // Obtener el id del valor que deseas preseleccionar

        // Aquí debes poner el valor deseado
        e.preventDefault()
        let idValorPCreseleccionado = ""
        idValorPCreseleccionado = id
        // Obtener el elemento select
        var miSelect = document
            .getElementById(
                "idSelect");
        let largo = miSelect.options.length
        let final = 0
        console.log(miSelect.options)
        // Iterar sobre las opciones y seleccionar la que coincida con el id
        for (var i = 0; i <
            largo; i++) {
            if (miSelect.options[i].value == idValorPCreseleccionado) {
                final = i
                break
            }
        }
        miSelect.options[final].selected = true

    };

    function formatoNumerico(input) {
        // Obtener el valor actual del input
        let valor = input.value;
        if (isNaN(valor)) {
            // Eliminar el último carácter ingresado si no es un número
            input.value = valor.slice(0, -1);
        } else {
            // Obtener el valor actual del input
            let valor = input.value;

            // Quitar cualquier carácter que no sea un dígito o un punto
            let valorSoloNumeros = valor.replace(/[^\d.]/g, "");

            // Separar la parte entera y la parte decimal
            let partes = valorSoloNumeros.split('.');

            // Formatear la parte entera con puntos
            let parteEntera = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Si hay parte decimal, reconstruir el valor con la parte decimal
            if (partes.length > 1) {
                let parteDecimal = partes[1].substring(0, 3); // Tomar hasta 3 dígitos de la parte decimal
                parteEntera += '.' + parteDecimal;
            }

            // Establecer el valor formateado en el input
            input.value = parteEntera;
        }

    }
    </script>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" style="font-weight: bold;">Agregar Novedad</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary">
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form method="post" action="../controller/agregarNovedad.php">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="fechayHora"><i class="fas fa-calendar-alt"></i> Fecha y
                                                    Hora:</label>
                                                <input type="datetime-local" class="form-control" id="fechayHora"
                                                    name="fechayHora" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="tipoNovedad"><i class="fas fa-info-circle"></i> Tipo de
                                                    Novedad:</label>
                                                <select class="form-control" id="tipoNovedad" name="tipoNovedad"
                                                    required>
                                                    <option value="Errores de Software">Errores de Software</option>
                                                    <option value="Problemas de Hardware">Problemas de Hardware</option>
                                                    <option value="Virus">Virus</option>
                                                    <option value="Conectividad y Red">Conectividad y Red</option>
                                                    <option value="Problemas de Periféricos">Problemas de Periféricos
                                                    </option>
                                                    <option value="Actualizaciones Pendientes">Actualizaciones
                                                        Pendientes</option>
                                                    <option value="Rendimiento">Rendimiento</option>
                                                    <option value="Problemas de Energía">Problemas de Energía</option>
                                                    <option value="Problemas de Sonido o Pantalla">Problemas de Sonido o
                                                        Pantalla</option>
                                                    <option value="Seguridad">Seguridad</option>
                                                    <option value="Otro">Otro</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="descripcion"><i class="fas fa-comment"></i> Descripción:</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                                required></textarea>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="estadoIntervencion"><i class="fas fa-wrench"></i> Estado de
                                                    Intervención:</label>
                                                <select class="form-control" id="estadoIntervencion"
                                                    name="estadoIntervencion" required>
                                                    <option value="0">Solucionado</option>
                                                    <option value="1">Pendiente de Verificación
                                                    </option>
                                                    <option value="2">Errores de Software</option>
                                                    <option value="3">Cerrado</option>
                                                    <option value="4">No Solucionado</option>
                                                    <option value="5">Reprogramado</option>
                                                    <option value="6">Aceptado por el Usuario</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="recomendaciones"><i class="fas fa-lightbulb"></i>
                                                    Recomendaciones:</label>
                                                <textarea class="form-control" id="recomendaciones"
                                                    name="recomendaciones" rows="3" required></textarea>
                                            </div>
                                        </div>

                                        <!-- Aquí puedes agregar tus selects con las opciones que necesitas -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="idEquipo"><i class="fas fa-laptop"></i> Equipo:</label>
                                                <!-- Ejemplo de select con opciones que debes llenar desde la base de datos -->
                                                <select class="form-control" id="idEquipo" name="idEquipo" required>
                                                    <?php
                                                $sql2 = "SELECT * FROM equipos";
                                                $stmt2 = $pdo->prepare($sql2);
                                                $stmt2->execute();
                                                $fila2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($fila2 as $key) {
                                                    ?>
                                                    <option value="<?php echo $key['idEquipos'] ?>">
                                                        <?php echo $key['nombre'] ." - ". $key['numeroSerie']?>
                                                    </option>
                                                    <?php
                                                    } 
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="Personal_Mantenimiento_idPersonal_Mantenimiento"><i
                                                        class="fas fa-tools"></i> Personal de Mantenimiento:</label>
                                                <!-- Ejemplo de select con opciones que debes llenar desde la base de datos -->
                                                <select class="form-control" id="personal_mantenimiento"
                                                    name="personal_mantenimiento" required>
                                                    <?php
                                                    $sql3 = "SELECT * FROM personal_mantenimiento";
                                                    $stmt3 = $pdo->prepare($sql3);
                                                    $stmt3->execute();
                                                    $fila3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($fila3 as $key) {
                                                        ?>
                                                    <option value="<?php echo $key['idPersonal_Mantenimiento'] ?>">
                                                        <?php echo $key['nombre'] ." - ". $key['apellido']?>
                                                    </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                            Guardar</button>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="wrapper">


        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->

                <!-- Messages Dropdown Menu -->

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="../assets/img/Logo_Bellavista.jpg.jpeg" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span style="font-size: 85%;" class="brand-text font-weight-light">INVERSIONES BELLAVISTA</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../assets/img/Logo_Bellavista.jpg.jpeg" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $_SESSION['nombre'] ?></a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="./inicio.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Inicio
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">PERSONAS</li>
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-user"></i>
                                <p>
                                    Agregar
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./empleados.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Empleados</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./personal.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Personal de Mantenimiento</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">CARGOS</li>
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-plus-square"></i>
                                <p>
                                    Agregar
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./cargos.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cargo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./proveedor.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Proveedor</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">PROVEEDORES</li>
                        <li class="nav-item">
                            <a href="./proveedor.php" class="nav-link">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    Agregar Proveedor
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">EQUIPOS</li>
                        <li class="nav-item">
                            <a href="./equipos.php" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-desktop"></i>
                                <p>
                                    Registrar Equipo
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">NOVEDADES</li>
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <a href="./novedad.php" class="nav-link active">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>
                                Registrar Novedad
                            </p>
                        </a>
                        <li class="nav-header">CERRAR SESSION</li>
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <a href="./novedad.php" class="nav-link">
                            <i class="fas fa-sign-out-alt nav-icon"></i>
                            <p>
                                SALIR
                            </p>
                        </a>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Administrar Novedades</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./inicio.php">Inicio</a>
                                </li>
                                <li class="breadcrumb-item active">Novedades</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" data-toggle="modal" data-target="#modal-default"
                                class="btn btn-success">Agregar <i style="margin-left: 5px;"
                                    class="fas fa-plus-circle"></i></button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Fecha y Hora</th>
                                        <th>Tipo</th>
                                        <th>Equipo Afectado</th>
                                        <th>Persona que Registra</th>
                                        <th>Persona de Mantenimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
              foreach ($fila as $key) {
                /*
                   <option value="0">En uso</option>
                                                    <option value="1">En mantenimiento</option>
                                                    <option value="2">Desuso</option>
                                                    <option value="3">En proceso de configuración</option>
                                                    <option value="4">Retirado del inventario</option>
                                                    <option value="5">Dañada</option>
                                                    <option value="7">Apagado</option> 
                */
                
              ?>
                                    <tr>
                                        <td><?php echo $key['idNovedades'] ?></td>
                                        <td><?php echo $key['fechayHora'] ?></td>
                                        <td><?php echo $key['tipoNovedad'] ?></td>
                                        <td><?php echo $key['numeroSerie'] ?></td>
                                        <td><?php echo $key['nombreAdmin'] ?></td>
                                        <td><?php echo $key['nombrePersonal'] ?></td>
                                        <td><a class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-info<?php echo $key['idNovedades'] ?>"><i
                                                    class=" fas fa-solid fa-eye"></i></a>
                                        </td>
                                        <div class="modal fade" id="modal-info<?php echo $key['idNovedades'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" style="font-weight: bold;">Ver
                                                            Informacion
                                                        </h2>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card card-primary">
                                                                        <!-- /.card-header -->
                                                                        <!-- form start -->
                                                                        <form method="post" action="">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="nombre"><i
                                                                                            class="fas fa-user"></i>
                                                                                        Nombre:</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="nombre"
                                                                                        name="nombre"
                                                                                        value="<?php echo $key['nombre'] ?>"
                                                                                        required readonly>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="numeroSerie"><i
                                                                                            class="fas fa-barcode"></i>
                                                                                        Número de Serie:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="numeroSerie"
                                                                                        name="numeroSerie"
                                                                                        value="<?php echo $key['numeroSerie'] ?>"
                                                                                        required readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="fechaAdquisicion"><i
                                                                                            class="fas fa-calendar-alt"></i>
                                                                                        Fecha de Adquisición:</label>
                                                                                    <input type="date"
                                                                                        value="<?php echo $key['fechaAquisicion'] ?>"
                                                                                        class="form-control"
                                                                                        id="fechaAdquisicion"
                                                                                        name="fechaAdquisicion" required
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="garantia"><i
                                                                                            class="fas fa-shield-alt"></i>
                                                                                        Garantía:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="garantia" name="garantia"
                                                                                        value="<?php echo ($key['garantia'] == 0) ? 'Sí' : 'No' ?>"
                                                                                        readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="ultimoMantenimiento"><i
                                                                                            class="fas fa-wrench"></i>
                                                                                        Último Mantenimiento:</label>
                                                                                    <input type="date"
                                                                                        class="form-control"
                                                                                        id="ultimoMantenimiento"
                                                                                        name="ultimoMantenimiento"
                                                                                        value="<?php echo $key['ultimoMantenimiento'] ?>"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="valor"><i
                                                                                            class="fas fa-dollar-sign"></i>
                                                                                        Valor:</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="valor"
                                                                                        name="valor"
                                                                                        value="<?php echo number_format($key['valor'],0,".",",")  ?>"
                                                                                        required readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="estado"><i
                                                                                            class="fas fa-list-alt"></i>
                                                                                        Estado:</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="estado"
                                                                                        name="estado" value="<?php 
                                                                                        $estado="";
                                                                                        if($key['estado']== 0){
                                                                                            $estado = "En Uso";
                                                                                        }else if($key['estado']== 1){
                                                                                            $estado = "En mantenimiento";
                                                                                        }else if($key['estado']== 2){
                                                                                            $estado = "En Desuso";
                                                                                        }
                                                                                        else if($key['estado']== 3){
                                                                                            $estado = "En proceso de configuración";
                                                                                        }
                                                                                        else if($key['estado']== 4){
                                                                                            $estado = "Retirado del inventario";
                                                                                        }
                                                                                        else if($key['estado']== 5){
                                                                                            $estado = "Dañada";
                                                                                        }
                                                                                        else if($key['estado']== 7){
                                                                                            $estado = "Apagado";
                                                                                        } 
                                                                                        echo $estado?>" readonly>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="usuario"><i
                                                                                            class="fas fa-user"></i>
                                                                                        Usuario:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="usuario" name="usuario"
                                                                                        value="<?php echo($key['nombreUsuario']); ?>"
                                                                                        readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="proveedor"><i
                                                                                        class="fas fa-building"></i>
                                                                                    Proveedor:</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="proveedor" name="proveedor"
                                                                                    value="<?php echo ($key['nombreProvee']); ?>"
                                                                                    readonly>
                                                                            </div>

                                                                            <button type="submit"
                                                                                class="btn btn-primary"><i
                                                                                    class="fas fa-save"></i>
                                                                                Descargar Reporte</button>
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-default<?php echo $key['idNovedades'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" style="font-weight: bold;">Editar
                                                            Equipo
                                                        </h2>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card card-primary">
                                                                        <!-- /.card-header -->
                                                                        <!-- form start -->
                                                                        <form method="post"
                                                                            action="../controller/editarEquipo.php">
                                                                            <input type="hidden" class="form-control"
                                                                                id="idEquipos" name="idEquipos"
                                                                                value="<?php echo $key['idEquipos'] ?>"
                                                                                required>
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="nombre"><i
                                                                                            class="fas fa-user"></i>
                                                                                        Nombre:</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="nombre"
                                                                                        name="nombre"
                                                                                        value="<?php echo $key['nombre'] ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="numeroSerie"><i
                                                                                            class="fas fa-barcode"></i>
                                                                                        Número de
                                                                                        Serie:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="numeroSerie"
                                                                                        name="numeroSerie"
                                                                                        value="<?php echo $key['numeroSerie'] ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="fechaAdquisicion"><i
                                                                                            class="fas fa-calendar-alt"></i>
                                                                                        Fecha
                                                                                        de Adquisición:</label>
                                                                                    <input type="date" rea
                                                                                        value="<?php echo $key['fechaAquisicion'] ?>"
                                                                                        class="form-control"
                                                                                        id="fechaAdquisicion"
                                                                                        name="fechaAdquisicion"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="garantia"><i
                                                                                            class="fas fa-shield-alt"></i>
                                                                                        Garantía:</label>
                                                                                    <select class="form-control"
                                                                                        id="garantia" name="garantia"
                                                                                        required>
                                                                                        <option value="0">Sí</option>
                                                                                        <option value="1">No</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="ultimoMantenimiento"><i
                                                                                            class="fas fa-wrench"></i>
                                                                                        Último
                                                                                        Mantenimiento:</label>
                                                                                    <input type="date"
                                                                                        class="form-control"
                                                                                        id="ultimoMantenimiento"
                                                                                        name="ultimoMantenimiento"
                                                                                        value="<?php echo $key['ultimoMantenimiento'] ?>">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="valor"><i
                                                                                            class="fas fa-dollar-sign"></i>
                                                                                        Valor:</label>
                                                                                    <input type="number" va
                                                                                        class="form-control" id="valor"
                                                                                        name="valor"
                                                                                        value="<?php echo $key['valor'] ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="estado"><i
                                                                                            class="fas fa-list-alt"></i>
                                                                                        Estado:</label>
                                                                                    <select class="form-control"
                                                                                        id="estado" name="estado"
                                                                                        required>
                                                                                        <option value="0">En uso
                                                                                        </option>
                                                                                        <option value="1">En
                                                                                            mantenimiento</option>
                                                                                        <option value="2">Desuso
                                                                                        </option>
                                                                                        <option value="3">En proceso de
                                                                                            configuración</option>
                                                                                        <option value="4">Retirado del
                                                                                            inventario</option>
                                                                                        <option value="5">Dañada
                                                                                        </option>
                                                                                        <option value="6">En Uso
                                                                                        </option>
                                                                                        <option value="7">Apagado
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="usuario"><i
                                                                                            class="fas fa-user"></i>
                                                                                        Usuario:</label>
                                                                                    <!-- Ejemplo de select con buscador -->
                                                                                    <select class="form-control"
                                                                                        id="usuario" name="usuario"
                                                                                        required>
                                                                                        <?php
                                                      $sql2 = "SELECT * FROM usuarios WHERE estado=0";
                                                      $stmt2 = $pdo->prepare($sql2);
                                                      $stmt2->execute();
                                                      $fila2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                                      foreach ($fila2 as $key) {
                                                    ?>
                                                                                        <option
                                                                                            value="<?php echo $key['idUsuarios'] ?>">
                                                                                            <?php echo $key['nombre']  ?>
                                                                                        </option>

                                                                                        <?php
                                                    }
                                                    ?>
                                                                                        <!-- Agrega opciones según tus usuarios en la base de datos -->
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="proveedor"><i
                                                                                        class="fas fa-building"></i>
                                                                                    Proveedor:</label>
                                                                                <!-- Ejemplo de select con buscador -->
                                                                                <select class="form-control"
                                                                                    id="proveedor" name="proveedor"
                                                                                    required>
                                                                                    <?php
                                                      $sql3 = "SELECT * FROM proveedor WHERE estado=0";
                                                      $stmt3 = $pdo->prepare($sql3);
                                                      $stmt3->execute();
                                                      $fila3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                                                      foreach ($fila3 as $key) {
                                                    ?>
                                                                                    <option
                                                                                        value="<?php echo $key['idProveedor'] ?>">
                                                                                        <?php echo $key['nombre'] ?>
                                                                                    </option>
                                                                                    <?php
                                                    }
                                                    ?>
                                                                                    <!-- Agrega opciones según tus proveedores en la base de datos -->
                                                                                </select>
                                                                            </div>

                                                                            <button type="submit"
                                                                                class="btn btn-primary"><i
                                                                                    class="fas fa-save"></i>
                                                                                Guardar</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                        </div>

                                    </tr>
                                    <?php
              }
              ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Identificador</th>
                                        <th>Fecha y Hora</th>
                                        <th>Tipo</th>
                                        <th>Equipo Afectado</th>
                                        <th>Persona que Registra</th>
                                        <th>Persona de Mantenimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 | Diseñada por :<a href="https://adminlte.io">Juan
                    Jose Rincon
                    Gomez</a>.</strong>
            Todos Los Derechos Reservados.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.js"></script>
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="../dist/js/pages/dashboard.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../dist/js/pages/dashboard.js"></script>
    <script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print"],

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
    </script>
</body>

</html>