<?php
require_once('../database.php');
require_once('../index.php');
$response = new Response();

$data = json_decode(file_get_contents("php://input"), true);

$where_clause = "";
$filters = array();

try {

    if (!empty($data)) {
        $where_clause .= " WHERE ";
        foreach ($data as $key => $value) {
            $filters[] = "$key = '$value'";
        }
        $where_clause .= implode(" AND ", $filters);
    }

    $sql = "SELECT ISBN, Autor, Genero, Ano_de_publicacao, Editora, Status, Livro.Exemplar, Imagem, exemplar.Exemplar as exemplar_name FROM Livro 
    LEFT JOIN exemplar ON exemplar.ID_EXEMPLAR = Livro.Exemplar" . $where_clause;
    $query = $con->query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $numResult = $query->rowCount();

    if ($numResult > 0) {
        $response->setMessage('Dados recuperados com sucesso.');
        $response->setData($result);
        echo $response->jsonResponse();
    } else {
        $response->setMessage('Nenhum livro encontrado.');
        echo $response->jsonResponse();
    }
} catch (Exception $e) {
    if ($e->getCode() == 1) {
        $response->setStatus(400);
        $response->setMessage($e->getMessage());
    } else {
        $response->setStatus(500);
        $response->setMessage('Ocorreu um erro no processamento.');
        $response->setMessageErro($e->getMessage());
        $response->setSql($selectGetUsuario);
    }
    echo $response->jsonResponse();
}
