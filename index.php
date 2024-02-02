<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php
    if (isset($_SESSION['error'])) {
    ?>
        <script>
            let msj = '<?php echo $_SESSION['error'] ?>'
            Swal.fire(
                "Error al Ingresar",
                msj,
                'error'
            )
        </script>
    <?php
        unset($_SESSION['error']);
    }
    ?>
    <!-- Login 13 - Bootstrap Brain Component -->
<section class="bg-light py-3 py-md-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <div class="card border border-light-subtle rounded-3 shadow-sm">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="text-center mb-3">
                <img src="./assets/img/Logo_Bellavista.jpg.jpeg" alt="BootstrapBrain Logo" width="175" height="175">
            </div>
            <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Ingresa al Sistema</h2>
            <form action="./controller/login.php" method="post">
              <div class="row gy-2 overflow-hidden">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="number" min="0" class="form-control" name="cedula" id="cedula" placeholder="name@example.com" required>
                    <label for="email" class="form-label">Cedula</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password"  class="form-control" name="pass" id="pass" value="" placeholder="pass" required>
                    <label for="password" class="form-label">Contraseña</label>
                  </div>
                </div>
                <div class="col-12">
                </div>
                <div class="col-12">
                  <div class="d-grid my-3">
                    <button class="btn btn-primary btn-lg" type="submit">Ingresar</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>