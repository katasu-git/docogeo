<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function post_ex() {
    //dist_postに登録

    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $ex_id = $_POST['ex_id'];
    $posted_ex = $_POST['posted_ex'];
    $isHidden = $_POST['isHidden'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    dist_post (tour_id, spot_id, ex_id, posted_ex, isHidden) 
    VALUES (:tour_id, :spot_id, :ex_id, :posted_ex, :isHidden)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':ex_id', $ex_id, PDO::PARAM_INT);
    $stmt->bindParam(':posted_ex', $posted_ex, PDO::PARAM_STR);
    $stmt->bindValue(':isHidden', $isHidden, PDO::PARAM_INT);
    $stmt->execute();


    //spot_exを更新（ガイド側に反映）

    $sql = "UPDATE spot_explanation SET sended = :sended, isPosted = :isPosted WHERE id = $ex_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 1, ':sended' => $date);
    $stmt->execute($params);

}

post_ex();


?>