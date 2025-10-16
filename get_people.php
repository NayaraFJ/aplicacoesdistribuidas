<?php

require 'database.php';

try {
    $stmt = $conn->query('SELECT * FROM pessoas');
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tasksArray = array();
    foreach($tasks as $task){
        if($task['status']==1){
            $tasks['status2']="Ativo";
        }else{
            $tasks['status2']="Inativo";
        }
        array_push($tasksArray, $task);
    }
    echo json_encode(array("data" => $tasksArray));
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>