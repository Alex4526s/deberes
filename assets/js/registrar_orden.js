  document.addEventListener('DOMContentLoaded', function () {
    const error = document.getElementById('errorMessage');
    const success = document.getElementById('successMessage');

    if (error) {
        setTimeout(() => {
            error.style.opacity = '0';
            setTimeout(() => error.remove(), 500);
        }, 4000);
    }

    if (success) {
        setTimeout(() => {
            success.style.opacity = '0';
            setTimeout(() => success.remove(), 500);
        }, 4000);
    }
});
