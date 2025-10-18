<?php

//Abre a conexão com o mysql
require 'database.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

   /* $novoHash = md5('123'); // mantém compatível com o login atual

    $stmt = $conn->prepare('UPDATE login SET senha = :hash WHERE usuario = :id');
    $stmt->execute([
        ':hash' => $novoHash,
        ':id'   => 1, // usuário 1
    ]);*/

    try {
        $stmt = $conn->prepare(' SELECT * '
                . ' FROM usuarios u, login l '
                . ' WHERE u.idusuario = l.usuario '
                . ' AND u.email = :email '
                . ' AND l.senha = :senha '
                . ' AND u.status = 1 ');
        $stmt->execute([':email' => $email, ':senha' => md5($password)]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Verifica se há resultados
    if ($usuarios) {
        // Caso encontre grava os dados na sessão e redireciona para a area de gestão de usuários
        foreach ($usuarios as $usuario) {
            $data = date("Y-m-d");
            $hora = date("H:i:s");
            $host = $_SERVER["REMOTE_ADDR"];
            
            session_start();
            $_SESSION['id_usuario']=$usuario['idusuario'];
            $_SESSION['nome_usuario']=$usuario['nome'];
            $_SESSION['email_usuario']=$usuario['email'];
            $_SESSION['data']=$data;
            $_SESSION['hora']=$hora;
            $_SESSION['host']=$host;
            
            //registra automaticamente a data e hora do último login
            $stmt = $conn->prepare(' UPDATE login '
                . ' SET data = :data, '
                . ' hora = :hora, '
                . ' host = :host '
                . ' WHERE usuario = :usuario ');
            $stmt->execute([':data' => $data, ':hora' => $hora, ':host' => $host, ':usuario' => $usuario['idusuario']]);
        
            
            header("Location: dados_pessoais.php");
        }
        
    } else {
        header("Location: index.php");
    }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    header("Location: index.php");
}
?>
