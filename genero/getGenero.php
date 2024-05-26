<?php
require_once('../database.php');
require_once('../index.php');
$response = new Response();

$data = json_decode(file_get_contents("php://input"), true);

try {
    $where_clause = "";

    if (!empty($data)) {
        $filters = array();
        $where_clause .= " WHERE ";
        foreach ($data as $key => $value) {
            $filters[] = "$key = '$value'";
        }
        $where_clause .= implode(" AND ", $filters);
    }

    $sql = "SELECT * FROM genero $where_clause";
    $query = $con->query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $numResult = $query->rowCount();

    if ($numResult > 0) {
        $response->setMessage('Dados recuperados com sucesso.');
        $response->setData($result);
        echo $response->jsonResponse();
    } else {
        $response->setMessage('Nenhum gênero encontrado.');
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
        $response->setSql($sql);
    }
    echo $response->jsonResponse();
}
