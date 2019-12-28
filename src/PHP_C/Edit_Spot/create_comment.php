<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function create_comment() {
    //必要な引数を用意
    $comment = $_POST['comment'];
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    spot_explanation (spot_explanation, tour_id, spot_id) 
    VALUES (:spot_explanation, :tour_id, :spot_id)");
    $stmt->bindValue(':spot_explanation', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);

    $stmt->execute();

}

create_comment();

?>