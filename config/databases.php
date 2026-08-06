<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "temps_libre";


try {


$conn = new PDO(

"mysql:host=$host;dbname=$database;charset=utf8",

$user,

$password

);


$conn->setAttribute(

PDO::ATTR_ERRMODE,

PDO::ERRMODE_EXCEPTION

);


}

catch(PDOException $e){


die(
"Erreur connexion : ".$e->getMessage()
);


}

?>