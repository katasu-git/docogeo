<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_db($data) {

    $pdo = connect_mysql();  //mysqlに接続

    //dist_postに登録
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $spot_image_id = $_POST['spot_image_id'];
    $image_path = $_POST['image_path'];

    $stmt = $pdo -> prepare("INSERT INTO dist_post (tour_id, spot_id, spot_image_id, image_path) VALUES (:tour_id, :spot_id, :spot_image_id, :image_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_image_id', $spot_image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();

    //スポットの更新
    $sql = "UPDATE spot_image SET isPosted = :isPosted WHERE id = $spot_image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isPosted' => 1);
    $stmt->execute($params);
}

try {
    echo json_encode(add_img_db($_POST['image_data'])); //偶数が線画像
} catch(PDOException $e) {
    echo $e;
    return $e;
}

?>