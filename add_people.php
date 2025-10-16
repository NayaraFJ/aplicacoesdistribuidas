<?php

require 'database.php';

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $status = $_POST['status'];
    try {
        $stmt = $conn->prepare('INSERT INTO pessoas (idpessoas, nome, status) VALUES (null, :nome, :status)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $taskId = $conn->lastInsertId();
        echo json_encode(['idpessoas' => $taskId, 'nome' => $nome, 'status' => $status]);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'O nome é obrigatório']);
}

?>