<?php

require_once("../connect_mysql.php");

function delete_posted_post() {
    $ex_id = $_POST['ex_id'];
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted WHERE ex_id = :ex_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':ex_id' => $ex_id, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_posted_post();

?>