document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.querySelector('.mensaje');
    if (mensaje) {
      setTimeout(() => {
        mensaje.style.opacity = '0';
        setTimeout(() => mensaje.remove(), 500);
      }, 3000);
    }
  });
  