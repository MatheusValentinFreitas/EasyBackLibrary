<?php
require_once('../database.php');
require_once('../index.php');

$data = json_decode(file_get_contents("php://input"), true);

$data_emprestimo = $data['data_emprestimo'];
$data_devolucao = $data['data_devolucao'];

$sql = "INSERT INTO Emprestimo (Data_emprestimo, Data_devolucao) 
        VALUES ('$data_emprestimo', '$data_devolucao')";

if (!$con->query($sql)) {
    echo "Erro ao inserir empréstimo.";
} else {
    echo json_encode(array("id" => $con->lastInsertId()));
}

