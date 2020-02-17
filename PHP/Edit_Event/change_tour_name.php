<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function chage_tour_name() {
    $tour_id = $_POST['tour_id'];
    $tour_name_changed = $_POST['tour_name_changed'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "UPDATE tour_name SET tour_name = :tour_name, updated = :updated WHERE id = $tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':tour_name' => $tour_name_changed, 'updated' => $date);
    $stmt->execute($params);

}

chage_tour_name();

?>