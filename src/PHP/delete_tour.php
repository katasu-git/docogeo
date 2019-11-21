<?php

require_once("./connect_mysql.php");

function delete_tour() {
    $ex_id = $_POST['tour_id'];
    $date = date("Y/m/d H:i:s");

    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE tour_name SET isDeleted = :isDeleted, deleted = :deleted WHERE id = :id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':id' => $ex_id, 'deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_tour();

?>