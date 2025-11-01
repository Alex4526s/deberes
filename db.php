<?php
$host = "localhost";
$usuario = "root";           
$clave = "";                 
$base_datos = "hornipan";    

$conn = new mysqli($host, $usuario, $clave, $base_datos);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
?>