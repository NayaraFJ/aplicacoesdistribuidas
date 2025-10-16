<?php
//Esse sesion_start() inicia a sessão dando acesso ao vetor $_session[]
    session_start();

    if(IsSet($_SESSION['id_usuario'])){
            //coletamos os dados existentes no vetor...
        $senha_usuario = $_SESSION['id_usuario'];
        $nome_usuario = $_SESSION['nome_usuario'];
        $email_usuario = $_SESSION['email_usuario'];
        $data = $_SESSION['data'];
        $hora = $_SESSION['hora'];
        $host = $_SESSION['host'];
    }else{ 
    //aqui vamos verificar se temos algo registrado
        header("Location: index.php");
    }    
?>
