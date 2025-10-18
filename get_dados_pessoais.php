<?php

require 'database.php';

try {
    $stmt = $conn->query('SELECT matricula, nome, email, celular, data_nascimento FROM DadosPessoais ORDER BY nome');
    $records = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dataNascimento = $row['data_nascimento'];
        $row['data_nascimento_br'] = $dataNascimento ? date('d/m/Y', strtotime($dataNascimento)) : null;
        $records[] = $row;
    }

    echo json_encode(['data' => $records]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

