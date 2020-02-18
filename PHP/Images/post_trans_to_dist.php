<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_db() {

    $pdo = connect_mysql();  //mysqlに接続

    //trans_imageに登録
    $image_path = $_POST['image_path'];
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $spot_image_id = $_POST['spot_image_id'];
    $opacity = $_POST['opacity'];;

    $stmt = $pdo -> prepare("INSERT INTO trans_image (tour_id, spot_id, spot_image_id, image_path, opacity) VALUES (:tour_id, :spot_id, :spot_image_id, :image_path, :opacity)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_image_id', $spot_image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->bindValue(':opacity', $opacity);
    $stmt->execute();
}

add_img_db();

?>