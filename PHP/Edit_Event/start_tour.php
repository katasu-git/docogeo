<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function start_tour() {
    $tour_id = $_POST['tour_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();
    $sql = "UPDATE tour_name SET isActive = :isActive, start_time = :start_time WHERE id = $tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isActive' => 1, ':start_time' => $date);
    $stmt->execute($params);
}

start_tour();



?>