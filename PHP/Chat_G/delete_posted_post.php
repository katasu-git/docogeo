<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function delete_posted_post() {
    $ex_id = $_POST['ex_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted WHERE ex_id = :ex_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, ':ex_id' => $ex_id, ':deleted' => $date);
    $stmt->execute($params);

    //ガイドがわを更新
    $sql = "UPDATE spot_explanation SET isPosted = :isPosted WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 0, ':id' => $ex_id);
    $stmt->execute($params);
}

delete_posted_post();

?>