$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault();

        const username = $('#username').val();
        const password = $('#password').val();

        if (username === 'admin' && password === 'password') {  // Cambiar por autenticación real
            window.location.href = 'index.html';
        } else {
            $('#errorMessage').text('Usuario o contraseña incorrectos.');
        }
    });
});
