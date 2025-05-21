// Variable Declaration

const loginBtn = document.querySelector("#login");
const registerBtn = document.querySelector("#register");
const loginForm = document.querySelector(".login-form");
const registerForm = document.querySelector(".register-form");

// Login button function
loginBtn.addEventListener('click', () =>{
    loginBtn.style.backgroundColor = "#32a7e2";
    registerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";

    loginForm.style.left = "50%";
    registerForm.style.left = "-50%";

    loginForm.style.opacity =1;
    registerForm.style.opacity = 0;


});

// Register button function

registerBtn.addEventListener('click', () => {
    loginBtn.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
    registerBtn.style.backgroundColor = "#32a7e2";

    loginForm.style.left = "150%";
    registerForm.style.left = "50%";

    loginForm.style.opacity = 0;
    registerForm.style.opacity = 1;


});

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    // Validación del formulario de inicio de sesión
    loginForm.addEventListener('submit', function(event) {
        const username = loginForm.querySelector('input[type="text"]').value;
        const password = loginForm.querySelector('input[type="password"]').value;

        if (!username || !password) {
            event.preventDefault(); // Evita el envío del formulario
            alert('Por favor, complete todos los campos.');
        }
    });

    // Validación del formulario de registro
    registerForm.addEventListener('submit', function(event) {
        const email = registerForm.querySelector('input[type="email"]').value;
        const username = registerForm.querySelectorAll('input[type="text"]')[1].value; // Segundo input de texto (username)
        const password = registerForm.querySelector('input[type="password"]').value;

        // Validar si los campos están completos
        if (!email || !username || !password) {
            event.preventDefault(); // Evita el envío del formulario
            alert('Por favor, complete todos los campos.');
        } else {
            // Validar el formato del correo electrónico
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                event.preventDefault(); // Evita el envío del formulario
                alert('Por favor, ingrese un correo electrónico válido.');
            }

            // Validación de la contraseña
            const passwordPattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;
            if (!passwordPattern.test(password)) {
                event.preventDefault(); // Evita el envío del formulario
                alert('La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, un número y un carácter especial.');
            }
        }
    });
});
