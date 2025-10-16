<?php

require 'database.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim(filter_input(INPUT_POST, 'nome_completo', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$dataNascimento = filter_input(INPUT_POST, 'data_nascimento');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$endereco = trim(filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cidade = trim(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$estado = strtoupper(trim(filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT, ['options' => ['default' => 1]]);
$observacoes = trim(filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if ($dataNascimento === '') {
    $dataNascimento = null;
}

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'O identificador é obrigatório.']);
    exit;
}

if (empty($nome)) {
    http_response_code(400);
    echo json_encode(['error' => 'O nome completo é obrigatório.']);
    exit;
}

if ($email === false) {
    $email = null;
} elseif ($email !== null) {
    $email = trim($email);
}

$cpf = $cpf !== '' ? $cpf : null;
$telefone = $telefone !== '' ? $telefone : null;
$endereco = $endereco !== '' ? $endereco : null;
$cidade = $cidade !== '' ? $cidade : null;
$estado = $estado !== '' ? $estado : null;
$observacoes = $observacoes !== '' ? $observacoes : null;

if (!in_array($status, [0, 1], true)) {
    $status = 1;
}

try {
    $stmt = $conn->prepare('UPDATE dados_pessoais SET nome_completo = :nome_completo, cpf = :cpf, data_nascimento = :data_nascimento, email = :email, telefone = :telefone, endereco = :endereco, cidade = :cidade, estado = :estado, status = :status, observacoes = :observacoes WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':nome_completo', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':cpf', $cpf, $cpf === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':data_nascimento', $dataNascimento, $dataNascimento === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, $email === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':telefone', $telefone, $telefone === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':endereco', $endereco, $endereco === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':cidade', $cidade, $cidade === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':estado', $estado, $estado === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':observacoes', $observacoes, $observacoes === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

