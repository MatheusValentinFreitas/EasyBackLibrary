<?php
require_once('../database.php');
require_once('../index.php');

$data = json_decode(file_get_contents("php://input"), true);

$where_clause = "";

if (!empty($data)) {
    $where_clause .= " WHERE ";
    $filters = array();
    foreach ($data as $key => $value) {
        $filters[] = "$key = '$value'";
    }
    $where_clause .= implode(" AND ", $filters);
}

$sql = "SELECT * FROM usuario $where_clause";
$result = $conn->query($sql);

$usuarios = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuario = array(
            'CPF' => $row['CPF'],
            'Nome' => $row['Nome'],
            'Endereco' => $row['Endereco'],
            'Telefone' => $row['Telefone'],
            'Email' => $row['Email'],
            'Senha' => $row['Senha']
        );
        $usuarios[] = $usuario;
    }
    echo json_encode($usuarios);
} else {
    echo json_encode(array('message' => 'Nenhum usuário encontrado'));
}
$conn->close();
