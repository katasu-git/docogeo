<?php

require_once("../connect_mysql.php");

function delete_spot() {
    $spot_id = $_POST['spot_id'];
    $date = date("Y/m/d H:i:s");

    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE spot_name SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $spot_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, 'deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_spot();

?>