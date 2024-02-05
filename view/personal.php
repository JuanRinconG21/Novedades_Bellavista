<?php
session_start();
include("../model/MySQL.php");
$conexion = new MySQL();
$pdo = $conexion->conectar();
$sql = "SELECT personal_mantenimiento.idPersonal_Mantenimiento, personal_mantenimiento.nombre,personal_mantenimiento.apellido, personal_mantenimiento.telefono, personal_mantenimiento.direccion, especialidad.idEspecialidad, especialidad.descripcion FROM personal_mantenimiento INNER JOIN especialidad ON personal_mantenimiento.Especialidad_idEspecialidad = especialidad.idEspecialidad WHERE personal_mantenimiento.estado = 0";
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
    function eliminarPersonal(id) {
        Swal.fire({
            title: `¿ Deseas Eliminar El Personal #${id} ?`,
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
            url: '../controller/eliminarPersonal.php', // Ajusta la ruta a tu controlador PHP
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
    </script>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" style="font-weight: bold;">Agregar Personal de Mantenimiento</h2>
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
                                    <form method="post" action="../controller/agregarPersonal.php">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="cedula">Cédula:</label>
                                                <input type="number" min="1" class="form-control" id="cedula"
                                                    name="cedula" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Nombre:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="apellido">Apellido:</label>
                                                <input type="text" class="form-control" id="apellido" name="apellido"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="telefono">Teléfono:</label>
                                                <input type="number" class="form-control" id="telefono" name="telefono"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="direccion">Direccion:</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="idCargo">Especialidad:</label>
                                                <select class="form-control" name="idCargo" required>
                                                    <?php
                                                    $sql2 = "SELECT * FROM especialidad WHERE estado=0";
                                                    $stmt2 = $pdo->prepare($sql2);
                                                    $stmt2->execute();
                                                    $fila2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($fila2 as $key) {
                                                    ?>
                                                    <!-- Aquí puedes insertar opciones dinámicamente desde tu base de datos o definirlas manualmente -->
                                                    <option value="<?php echo $key['idEspecialidad'] ?>">
                                                        <?php echo $key['descripcion'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <!-- Agrega más opciones según sea necesario -->
                                                </select>
                                            </div>
                                            <button type=" submit" class="btn btn-success">Agregar</button>
                                        </div>
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
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-user"></i>
                                <p>
                                    Agregar
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./empleados.php" class="nav-link ">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Empleados</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./personal.php" class="nav-link active">
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
                        <!-- Add icons to the links using the .nav-icon clas
                        with font-awesome or any other icon font library -->
                        <a href="./novedad.php" class="nav-link">
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
                            <h1 class="m-0">Administrar Personal</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./inicio.php">Inicio</a>
                                </li>
                                <li class="breadcrumb-item active">Personal</li>
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
                                        <th>Cedula</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Especialidad</th>
                                        <th>Eliminar</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
              foreach ($fila as $key) {
              ?>
                                    <tr>
                                        <td><?php echo $key['idPersonal_Mantenimiento'] ?></td>
                                        <td><?php echo $key['nombre'] ?></td>
                                        <td><?php echo $key['apellido'] ?></td>
                                        <td><?php echo $key['telefono'] ?></td>
                                        <td><?php echo $key['direccion'] ?></td>
                                        <td><?php echo $key['descripcion'] ?></td>
                                        <td><a class="btn btn-danger"
                                                onclick="eliminarPersonal(<?php echo $key['idPersonal_Mantenimiento'] ?>)"><i
                                                    class="fas fa-trash-alt"></i></a> </td>
                                        <td><a onclick="select(<?php echo $key['idPersonal_Mantenimiento'] ?>,event)"
                                                class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-default<?php echo $key['idPersonal_Mantenimiento'] ?>"><i
                                                    class="fas fa-edit"></i></a> </td>
                                        <div class="modal fade"
                                            id="modal-default<?php echo $key['idPersonal_Mantenimiento'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" style="font-weight: bold;">Editar
                                                            Personal
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
                                                                            action="../controller/editarPersonal.php">
                                                                            <div class="card-body">
                                                                                <div class="form-group">
                                                                                    <label for="cedula">Cédula:</label>
                                                                                    <input type="number" min="1"
                                                                                        value="<?php echo $key['idPersonal_Mantenimiento'] ?>"
                                                                                        class="form-control" id="cedula"
                                                                                        name="cedula" required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="nombre">Nombre:</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="nombre"
                                                                                        name="nombre"
                                                                                        value="<?php echo $key['nombre'] ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="apellido">Apellido:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        value="<?php echo $key['apellido'] ?>"
                                                                                        id="apellido" name="apellido"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="telefono">Teléfono:</label>
                                                                                    <input type="number"
                                                                                        value="<?php echo $key['telefono'] ?>"
                                                                                        class="form-control"
                                                                                        id="telefono" name="telefono"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="direccion">Direccion:</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="direccion"
                                                                                        value="<?php echo $key['telefono'] ?>"
                                                                                        name="direccion" required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="idCargo">Especialidad:</label>
                                                                                    <select class="form-control"
                                                                                        name="idCargo" required>
                                                                                        <?php
                                                    $sql2 = "SELECT * FROM especialidad WHERE estado=0";
                                                    $stmt2 = $pdo->prepare($sql2);
                                                    $stmt2->execute();
                                                    $fila2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($fila2 as $key) {
                                                    ?>
                                                                                        <!-- Aquí puedes insertar opciones dinámicamente desde tu base de datos o definirlas manualmente -->
                                                                                        <option
                                                                                            value="<?php echo $key['idEspecialidad'] ?>">
                                                                                            <?php echo $key['descripcion'] ?>
                                                                                        </option>
                                                                                        <?php
                                                    }
                                                    ?>
                                                                                        <!-- Agrega más opciones según sea necesario -->
                                                                                    </select>
                                                                                </div>
                                                                                <button type=" submit"
                                                                                    class="btn btn-success">Agregar</button>
                                                                            </div>
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
                                        <th>Cedula</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Especialidad</th>
                                        <th>Eliminar</th>
                                        <th>Editar</th>
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
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
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