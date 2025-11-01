document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formRegistro');
  
    form.addEventListener('submit', function (e) {
      const clave = form.clave.value;
      const confirmar = form.confirmar_clave.value;
  
      if (clave !== confirmar) {
        e.preventDefault();
        alert('⚠ Las contraseñas no coinciden.');
      }
    });
  });
  