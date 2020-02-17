<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function chage_spot_name() {
    $spot_id = $_POST['spot_id'];
    $tour_id = $_POST['tour_id'];
    $spot_name_changed = $_POST['spot_name_changed'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();
    $sql = "UPDATE spot_name SET spot_name = :spot_name, updated = :updated WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = array(':spot_name' => $spot_name_changed, ':id' => $spot_id, 'updated' => $date);
    $stmt->execute($params);

    //ツアーの更新
    $sql = "UPDATE tour_name SET updated = :updated WHERE id = $tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => $date);
    $stmt->execute($params);

    //スポットの更新
    $sql = "UPDATE spot_name SET updated = :updated WHERE id = $spot_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => $date);
    $stmt->execute($params);
    
}

chage_spot_name();

?>