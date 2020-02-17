<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function delete_tour() {
    $tour_id = $_POST['tour_id'];
    $date = date("Y/m/d H:i:s");

    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE tour_name SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $tour_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, 'deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

delete_tour();

?>