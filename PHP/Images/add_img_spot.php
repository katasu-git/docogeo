<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_spot() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_id = $_POST['image_id'];
    $image_path = $_POST['image_path'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO spot_image (tour_id, spot_id, image_id, image_path) VALUES (:tour_id, :spot_id, :image_id, :image_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();

    //画像が追加されたという
    $sql = "UPDATE images SET isAdded = :isAdded WHERE id = $image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isAdded' => 1);
    $stmt->execute($params);

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

add_img_spot();

?>