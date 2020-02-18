<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function delete_posted_post() {
    $spot_image_id = $_POST['spot_image_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted WHERE spot_image_id=$spot_image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, ':deleted' => $date);
    $stmt->execute($params);

    //ガイドがわを更新
    $sql = "UPDATE spot_image SET isPosted = :isPosted WHERE id=$spot_image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 0);
    $stmt->execute($params);
}

delete_posted_post();

?>