<?php

require 'database.php';

if ( (isset($_POST['idpessoas'])) && (isset($_POST['status'])) && (isset($_POST['nome'])) ) {
    $idpessoas = $_POST['idpessoas'];
    $nome = $_POST['nome'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare('UPDATE pessoas SET status = :status, nome = :nome WHERE idpessoas = :idpessoas');
        $stmt->bindParam(':idpessoas', $idpessoas);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'O ID da pessoa é obrigatório']);
}

?>