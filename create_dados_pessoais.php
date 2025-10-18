<?php

require 'database.php';

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

if (empty($nome)) {
    http_response_code(400);
    echo json_encode(['error' => 'O nome é obrigatório.']);
    exit;
}

try {
    $stmt = $conn->prepare('INSERT INTO DadosPessoais (nome, email, celular, data_nascimento) VALUES (:nome, :email, :celular, :data_nascimento)');
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':data_nascimento', $dataNascimento, $dataNascimento === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':celular', $celular, $celular === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();

    $matricula = $conn->lastInsertId();
    echo json_encode([
        'matricula' => $matricula,
        'nome' => $nome,
        'email' => $email,
        'celular' => $celular,
        'data_nascimento' => $dataNascimento,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

