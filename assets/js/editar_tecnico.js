document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditarTecnico');
  
    form.addEventListener('submit', function (e) {
      const correo = form.correo.value;
      if (!correo.includes('@')) {
        e.preventDefault();
        alert('⚠ Debes ingresar un correo válido.');
      }
    });
  });
  