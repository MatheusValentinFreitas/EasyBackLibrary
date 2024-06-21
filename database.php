<?php
require_once("classes/ObjRetorno.php");
$servername = "127.0.0.1:3306";
$username = "root";
$password = "";
$database = "EASY_LIBRARY";

$conn = new mysqli($servername, $username, $password, $database);
$con = new PDO("mysql:host=127.0.0.1:3306;dbname=EASY_LIBRARY", $username, $password);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
