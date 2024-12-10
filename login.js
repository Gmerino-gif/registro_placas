// Capturar el formulario y manejar el evento de envío
document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

    // Obtener los valores ingresados por el usuario
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    // Validar que los campos no estén vacíos
    if (!username || !password) {
        displayError("Por favor, completa todos los campos.");
        return;
    }

    // Preparar los datos para enviarlos al servidor
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    try {
        // Enviar los datos al servidor
        const response = await fetch("login.php", {
            method: "POST",
            body: formData,
        });

        // Analizar la respuesta del servidor
        const result = await response.text();

        if (response.ok) {
            if (result.includes("Bienvenido")) {
                // Redirigir o mostrar mensaje de éxito
                alert(result);
                window.location.href = "dashboard.html"; // Cambiar la URL según tu aplicación
            } else {
                displayError(result); // Mostrar mensaje de error
            }
        } else {
            displayError("Hubo un problema con el servidor. Intenta más tarde.");
        }
    } catch (error) {
        displayError("Ocurrió un error al procesar la solicitud.");
        console.error(error);
    }
});

// Función para mostrar mensajes de error
function displayError(message) {
    const errorMessage = document.getElementById("errorMessage");
    errorMessage.textContent = message;
}

