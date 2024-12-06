<link rel="stylesheet" type="text/css" href="../../css/style.css" media="screen" />
<div class="login-box">
  <h2>Login</h2>
  <form action="../../controlador/UsuarioControlador.php" method="POST" id="validacion">
    <div class="user-box">
      <input type="hidden" name="action" value="login">
      <input type="email" name="User" required="">
      <label>Username</label>
    </div>
    <div class="user-box">
      <input type="password" name="Password" required="">
      <label>Password</label>
    </div>
    <a href="#">
       <span></span>
      <span></span>
      <span></span>
      <span></span>
      <input class="boton" type="submit" value="Ingresar">
      </a>
  </form>
</div>
