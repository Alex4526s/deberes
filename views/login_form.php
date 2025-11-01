<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Hornipan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 🔥 Para responsive -->
  <link rel="stylesheet" href="assets/css/estiloLogin.css">
</head>

<body>

<div class="login-box">
  <img src="assets/logo.jpg" alt="Hornipan" class="logo">
  <h3>Inicio de sesión</h3>

  <form action="login.php" method="POST">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="clave" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>

  <?php if (isset($_GET['error'])): ?>
    <div class="error">
      <?php if ($_GET['error'] === 'clave'): ?>
        ⚠ Contraseña incorrecta
      <?php elseif ($_GET['error'] === 'usuario'): ?>
        ⚠ El usuario no existe
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['registro']) && $_GET['registro'] === 'clave_ok'): ?>
    <div class="success">
      ✅ Contraseña actualizada correctamente. Inicia sesión.
    </div>
  <?php endif; ?>
</div>

</body>
</html>
