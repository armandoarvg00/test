<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seguros Express asd</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/login.css">
    <link rel="icon" type="image/png" href="resources/images/logo2.png" />
    <script src="resources/js/login.js"></script>
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="login-wrapper my-auto">
            <div class="brand-wrapper">
            <img src="resources/images/logo.png" alt="logo" class="logo">
          </div>
            <h1 class="login-title">Inicia Sesión</h1>
            <form action="#!">
              <div class="form-group">
                <label for="email">Usuario</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Usuario">
              </div>
              <div class="form-group mb-4">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
              </div>
              <input name="login" id="login" class="btn btn-block login-btn" type="button" value="Iniciar Sesión">
            </form>
            <!--<a href="#!" class="forgot-password-link">Forgot password?</a>-->
          </div>
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="resources/images/login.jpg" alt="login image" class="login-img">
        </div>
      </div>
    </div>
  </main>
<!--  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
