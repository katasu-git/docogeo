<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function create_tour() {
    //必要な引数を用意
    $tour_name = $_POST['tour_name'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    tour_name (tour_name, updated) 
    VALUES (:tour_name, :updated)");
    $stmt->bindValue(':tour_name', $tour_name, PDO::PARAM_INT);
    $stmt->bindValue(':updated', $date);

    $stmt->execute();

}

create_tour();

?>