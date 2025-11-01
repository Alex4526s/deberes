document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('.alert');
    if (alert) {
      setTimeout(() => {
        alert.style.display = 'none';
      }, 4000);
    }
  });
  