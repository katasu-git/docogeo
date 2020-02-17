<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function delete_spot() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();
    $sql = "UPDATE spot_name SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $spot_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, 'deleted' => $date);
    $stmt->execute($params);

    //ツアーの更新
    $sql = "UPDATE tour_name SET updated = :updated WHERE id = $tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => $date);
    $stmt->execute($params);
}

delete_spot();

?>