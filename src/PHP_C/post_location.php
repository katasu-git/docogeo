<?php
require_once("./connect_mysql.php");

function post_location() {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $altitude = $_POST['altitude'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    location (tour_id, altitude) 
    VALUES (:tour_id, :altitude)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':altitude', $altitude, PDO::PARAM_INT);

    $stmt->execute();
}

post_location();

?>