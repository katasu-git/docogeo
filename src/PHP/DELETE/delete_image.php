<?php

require_once("../connect_mysql.php");

function delete_image() {
    $image_id = $_POST['image_id'];
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE images SET isDeleted = :isDeleted, deleted = :deleted WHERE id = :image_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':image_id' => $image_id, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_image();

?>