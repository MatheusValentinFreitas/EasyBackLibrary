<?php
require_once('../database.php');
require_once('../index.php');
require_once('../classes/funcoesValidadoras.php');
$response = new Response();

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

try {    
    $id = Filter::retornaCampoTratado($id, null, null, 'ID', false);

    if (!$id['result']) {
        throw new Exception($id['message'], 1);
    } else {
        $id = $id['string'];
        
        $selectValidaId = "SELECT * FROM Exemplar WHERE ID_EXEMPLAR = '$id'";
        $queryValidaId = $con->query($selectValidaId);
        $respostaValidaId = $queryValidaId->fetch(PDO::FETCH_ASSOC);

        if (empty($respostaValidaId)) {
            throw new Exception('O exemplar indicado não foi encontrado.', 1);
        } else {
            $nome = $respostaValidaId['Exemplar'];
        }
    }
    
    $sql = "DELETE FROM Exemplar WHERE ID_EXEMPLAR = '$id'";

    if (!$con->query($sql)) {
        throw new Exception('Ocorreu um erro ao tentar remover o exemplar', 1);
    } else {
        $response->setMessage("Exemplar $nome removido com sucesso.");
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
