<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function create_tour() {


    //必要な引数を用意
    $tour_name = $_POST['tour_name'];
    //$date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $stmt = $pdo -> prepare("INSERT INTO tour_name (tour_name) VALUES (:tour_name)");
    $stmt->bindParam(':tour_name', $tour_name, PDO::PARAM_STR);
    //$stmt->bindValue(":date", date("Y-m-d H:i:s", strtotime($date)), PDO::PARAM_STR);
    $stmt->execute();

    $result = $tour_name;
    return $result;

}

echo json_encode(create_tour());

?>