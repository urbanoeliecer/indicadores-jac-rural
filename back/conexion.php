<?php
// Activa todos los errores para facilitar la depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer la conexión a la base de datos
$host = "localhost";
$dbname = "bdsara"; // Nombre de tu base de datos
$username = "root"; // Tu usuario de base de datos
$password = ""; // Tu contraseña (si no tienes, está vacía)

// Usando PDO para la conexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>

