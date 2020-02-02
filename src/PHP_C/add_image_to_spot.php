<?php
require_once("./connect_mysql.php");

function add_image_to_spot() {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_id = $_POST['image_id'];
    $image_path = $_POST['image_path'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    spot_image (tour_id, spot_id, image_id, image_path) 
    VALUES (:tour_id, :spot_id, :image_id, :image_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
    $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);

    $stmt->execute();
}

add_image_to_spot();

?>