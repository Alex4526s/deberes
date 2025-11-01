document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error') || urlParams.has('registro')) {
      setTimeout(() => {
        window.location.href = "login.php";
      }, 3500);
    }
  });
  