<?php
require_once('../database.php');
require_once('../index.php');
require_once('../classes/funcoesValidadoras.php');
$response = new Response();


$data = json_decode(file_get_contents("php://input"), true);

$isbn = $data['isbn'] ?? null;
$autor = $data['autor'] ?? null;
$genero = $data['genero'] ?? null;
$ano_publicacao = $data['ano_publicacao'] ?? null;
$editora = $data['editora'] ?? null;
$status = $data['status'] ?? null;
$exemplar = $data['exemplar'] ?? null;
$imagem = $data['imagem'] ?? null;

try {



    $sql = "INSERT INTO Livro (ISBN, Autor, Genero, Ano_de_publicacao, Editora, Status, Exemplar, Imagem)
        VALUES ('$isbn', '$autor', '$genero', '$ano_publicacao', '$editora', '$status', '$exemplar', '$imagem')";

    if (!$con->query($sql)) {
        throw new Exception('Ocorreu um erro ao tentar criar um novo livro.', 1);
    } else {
        $response->setMessage("Livro ISBN: $isbn cadastrado com sucesso.");
    }

    echo $response->jsonResponse();
} catch (Exception $e) {
    if ($e->getCode() == 1) {
        $response->setStatus(400);
        $response->setMessage($e->getMessage());
    } else {
        $response->setStatus(500);
        $response->setMessage('Ocorreu um erro no processamento.');
        $response->setMessageErro($e->getMessage());
        $response->setSql($sql);
    }
    echo $response->jsonResponse();
}
