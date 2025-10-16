<?php

require 'database.php';

try {
    $stmt = $conn->query('SELECT * FROM dados_pessoais ORDER BY nome_completo');
    $records = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dataNascimento = $row['data_nascimento'];
        $row['data_nascimento_br'] = $dataNascimento ? date('d/m/Y', strtotime($dataNascimento)) : null;
        $row['status_texto'] = ((int) $row['status'] === 1) ? 'Ativo' : 'Inativo';
        $records[] = $row;
    }

    echo json_encode(['data' => $records]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

