// Script para manejar el formulario de registro

document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevenir el comportamiento predeterminado

    // Obtener los valores de los campos
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const errorMessage = document.getElementById("errorMessage");

    // Validar que las contraseñas coincidan
    if (password !== confirmPassword) {
        errorMessage.textContent = "Las contraseñas no coinciden";
        return;
    }

    // Validar longitud de contraseña (opcional, ejemplo: al menos 6 caracteres)
    if (password.length < 6) {
        errorMessage.textContent = "La contraseña debe tener al menos 6 caracteres";
        return;
    }

    // Preparar los datos para enviar al servidor
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    // Enviar los datos al servidor
    fetch("register.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.text())
        .then((result) => {
            if (result.includes("Registro exitoso")) {
                alert("Usuario registrado con éxito");
                window.location.href = "login.html"; // Redirigir al login
            } else {
                errorMessage.textContent = result; // Mostrar el error devuelto por el servidor
            }
        })
        .catch((error) => {
            errorMessage.textContent = "Ocurrió un error al registrar el usuario";
            console.error(error);
        });
});
