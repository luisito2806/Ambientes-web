<?php 

$host = "localhost";
$usuario = "root";
$password = "12345";
$baseDatos = "escuela";

$conexion = new mysqli(
    $host,
    $usuario,
    $password, 
    $baseDatos
);

if ($conexion->connect_error){
    die("error de conexion: ". $conexion->connect_error);
}
    

?>