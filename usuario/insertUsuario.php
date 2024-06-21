<?php
require_once('../database.php');
require_once('../index.php');
require_once('../classes/funcoesValidadoras.php');
$response = new Response();

$data = json_decode(file_get_contents("php://input"), true);


$cpf = $data['cpf'] ?? null;
$nome = $data['nome'] ?? null;
$endereco = $data['endereco'] ?? null;
$telefone = $data['telefone'] ?? null;
$email = $data['email'] ?? null;
$senha = $data['senha'] ?? null;

try {

    $cpf = Filter::retornaCampoTratado($cpf, null, 14, 'CPF');

    if (!$cpf['result']) {
        throw new Exception($cpf['message'], 1);
    } else {
        $cpf = $cpf['string'];

        $selectValidaCPF = "SELECT * FROM USUARIO WHERE CPF = '$cpf'";
        $queryValidaCPF = $con->query($selectValidaCPF);
        $respostaValidaCPF = $queryValidaCPF->fetch(PDO::FETCH_ASSOC);

        if (!empty($respostaValidaCPF)) {
            throw new Exception('Já existe um usuario cadastrado com esse CPF.', 1);
        }
    }

    $nome = Filter::retornaCampoTratado($nome, 100, null, 'Nome');

    if (!$nome['result']) {
        throw new Exception($nome['message'], 1);
    } else {
        $nome = $nome['string'];
    }

    $endereco = Filter::retornaCampoTratado($endereco, 200, null, 'Endereço');

    if (!$endereco['result']) {
        throw new Exception($endereco['message'], 1);
    } else {
        $endereco = $endereco['string'];
    }

    $telefone = Filter::retornaCampoTratado($telefone, 20, null, 'Telefone');

    if (!$telefone['result']) {
        throw new Exception($telefone['message'], 1);
    } else {
        $telefone = $telefone['string'];
    }

    $email = Filter::retornaCampoTratado($email, 100, null, 'E-mail');

    if (!$email['result']) {
        throw new Exception($email['message'], 1);
    } else {
        $email = $email['string'];
    }

    $senha = Filter::retornaCampoTratado($senha, 20, null, 'Senha', false);

    if (!$senha['result']) {
        throw new Exception($senha['message'], 1);
    } else {
        $senha = $senha['string'];
    }

    $cpf = ($cpf != null || empty($cpf)) ? "'" . $cpf . "'" : 'NULL';
    $nome = ($nome != null || empty($nome)) ? "'" . $nome . "'" : 'NULL';
    $endereco = ($endereco != null || empty($endereco)) ? "'" . $endereco . "'" : 'NULL';
    $telefone = ($telefone != null || empty($telefone)) ? "'" . $telefone . "'" : 'NULL';
    $email = ($email != null || empty($email)) ? "'" . $email . "'" : 'NULL';
    $senha = ($senha != null || empty($senha)) ? "'" . $senha . "'" : 'NULL';

    $sql = "INSERT INTO usuario (CPF, Nome, Endereco, Telefone, Email, Senha) 
            VALUES ($cpf, $nome, $endereco, $telefone, $email, $senha)";

    if (!$con->query($sql)) {
        throw new Exception('Ocorreu um erro ao tentar criar o novo usuario.', 1);
    } else {
        $response->setMessage("Usuario $nome cadastrado com sucesso.");
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
