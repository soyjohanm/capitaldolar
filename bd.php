<?php
  try {
    $bd_nombre = 'capitaldolar';
    $bd_usuario = 'root';
    $bd_clave = '';
    $bd_host = 'localhost';

    $conexion = new PDO("mysql:host=$bd_host;dbname=$bd_nombre", $bd_usuario, $bd_clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) { echo $e->getMessage(); }
?>
