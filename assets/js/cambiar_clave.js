document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCambioClave');
  
    form.addEventListener('submit', function (e) {
      const nuevaClave = form.nueva_clave.value;
      const confirmarClave = form.confirmar_clave.value;
  
      if (nuevaClave !== confirmarClave) {
        e.preventDefault();
        alert('❌ Las contraseñas no coinciden.');
      } else if (nuevaClave.length < 8) {
        e.preventDefault();
        alert('❌ La contraseña debe tener al menos 8 caracteres.');
      }
    });
  });
  