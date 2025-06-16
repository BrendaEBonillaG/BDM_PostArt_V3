<?php
session_start();
session_unset();  // Limpiar variables de sesión
session_destroy();  // Destruir la sesión completamente

header("Location: ../Login.html");
exit();
