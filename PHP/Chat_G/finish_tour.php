<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function finish_tour() {
    $tour_id = $_POST['tour_id'];

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "UPDATE tour_name SET isActive = :isActive WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isActive' => 0, ':id' => $tour_id);
    $stmt->execute($params);

}

function delete_all_posted_post() {
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();
    $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, ':deleted' => $date);
    $stmt->execute($params);

}

function reset_post() {
    $tour_id = $_POST['tour_id'];

    $pdo = connect_mysql();
    $sql = "UPDATE spot_explanation SET isPosted = :isPosted, likes = :likes WHERE tour_id=$tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 0, ':likes' => 0);
    $stmt->execute($params);

}

function reset_post_img() {
    $tour_id = $_POST['tour_id'];
    
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "UPDATE spot_image SET isPosted = :isPosted, likes = :likes WHERE tour_id=$tour_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 0, ':likes' => 0);
    $stmt->execute($params);

}

finish_tour();

delete_all_posted_post();

reset_post();

reset_post_img();

?>