<?php

require_once("./connect_mysql.php");

function delete_spot_ex() {
    $ex_id = $_POST['ex_id'];
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE spot_explanation SET isDeleted = :isDeleted, deleted = :deleted WHERE id = :id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':id' => $ex_id, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_spot_ex();

?>