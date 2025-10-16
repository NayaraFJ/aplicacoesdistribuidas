<?php

require 'database.php';

if (isset($_POST['idpessoas'])) {
    $idpessoa = $_POST['idpessoas'];
    try {
        $stmt = $conn->prepare('DELETE FROM pessoas WHERE idpessoas = :idpessoa');
        $stmt->bindParam(':idpessoa', $idpessoa);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'O ID da pessoa é obrigatório']);
}

?>