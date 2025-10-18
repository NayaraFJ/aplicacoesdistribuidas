<?php

require 'database.php';

$matricula = filter_input(INPUT_POST, 'matricula', FILTER_VALIDATE_INT);
$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$celular = trim(filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$dataNascimento = filter_input(INPUT_POST, 'data_nascimento');

if ($dataNascimento === '') {
    $dataNascimento = null;
}

if ($email === false) {
    $email = null;
} elseif ($email !== null) {
    $email = trim($email);
}

$celular = $celular !== '' ? $celular : null;

if (!$matricula) {
    http_response_code(400);
    echo json_encode(['error' => 'O identificador é obrigatório.']);
    exit;
}

if (empty($nome)) {
    http_response_code(400);
    echo json_encode(['error' => 'O nome é obrigatório.']);
    exit;
}

try {
    $stmt = $conn->prepare('UPDATE DadosPessoais SET nome = :nome, email = :email, celular = :celular, data_nascimento = :data_nascimento WHERE matricula = :matricula');
    $stmt->bindValue(':matricula', $matricula, PDO::PARAM_INT);
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':celular', $celular, $celular === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':data_nascimento', $dataNascimento, $dataNascimento === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

