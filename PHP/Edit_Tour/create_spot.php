<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function create_spot() {
    //必要な引数を用意
    $spot_name = $_POST['spot_name'];
    $tour_id = $_POST['tour_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $stmt = $pdo -> prepare("INSERT INTO 
    spot_name (spot_name, tour_id, updated) 
    VALUES (:spot_name, :tour_id, :updated)");
    $stmt->bindValue(':spot_name', $spot_name, PDO::PARAM_STR);
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':updated', $date);

    $stmt->execute();

    //ツアーの更新
    $sql = "UPDATE tour_name SET updated = :updated WHERE id = $tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => $date);
    $stmt->execute($params);

}

create_spot();

?>