<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function create_comment() {
    $comment = $_POST['comment'];
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $stmt = $pdo -> prepare("INSERT INTO 
    spot_explanation (spot_explanation, tour_id, spot_id, updated) 
    VALUES (:spot_explanation, :tour_id, :spot_id, :updated)");
    $stmt->bindValue(':spot_explanation', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':updated', $date);
    $stmt->execute();

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

create_comment();

?>