<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña - Hornipan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estiloLogin.css">

</head>

<body>

  <div class="login-box">
    <img src="assets/logo.jpg" alt="Hornipan" class="logo">
    <h3>Cambiar contraseña</h3>

    <form method="POST" id="formCambiarClave">
      <input type="password" name="nueva_clave" placeholder="Nueva contraseña" required>
      <input type="password" name="confirmar_clave" placeholder="Confirmar nueva contraseña" required>
      <button type="submit">Guardar nueva contraseña</button>
    </form>

    <!-- 🛑 Mensaje de error dinámico -->
    <div id="mensajeError" class="mensaje-error"></div>

    <a href="logout.php" class="btn-secondary">⬅ Cerrar sesión</a>
  </div>

  <!-- ✅ JS interno -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById("formCambiarClave");
      const mensajeError = document.getElementById("mensajeError");

      form.addEventListener("submit", function (e) {
        const nuevaClave = form.nueva_clave.value.trim();
        const confirmarClave = form.confirmar_clave.value.trim();

        if (nuevaClave !== confirmarClave) {
          e.preventDefault();
          mensajeError.textContent = "⚠ Las contraseñas no coinciden.";
          mensajeError.style.display = "block";
        } else {
          mensajeError.style.display = "none"; // Ocultar si todo está correcto
        }
      });
    });
  </script>

</body>
</html>
