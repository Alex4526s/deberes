document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.querySelector('.mensaje');
    if (mensaje) {
      setTimeout(() => {
        mensaje.style.display = 'none';
      }, 5000);
    }
  });
  