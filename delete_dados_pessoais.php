<?php

require 'database.php';

$matricula = filter_input(INPUT_POST, 'matricula', FILTER_VALIDATE_INT);

if (!$matricula) {
    http_response_code(400);
    echo json_encode(['error' => 'O identificador é obrigatório.']);
    exit;
}

try {
    $stmt = $conn->prepare('DELETE FROM DadosPessoais WHERE matricula = :matricula');
    $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

