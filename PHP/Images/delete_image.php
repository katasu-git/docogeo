<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function delete_image() {
    $image_id = $_POST['image_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();
    $sql = "UPDATE images SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, ':deleted' => $date);
    $stmt->execute($params);

    //紐づいている画像を削除
    $sql = "UPDATE spot_image SET isDeleted = :isDeleted, deleted = :deleted WHERE image_id = $image_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, 'deleted' => $date);
    $stmt->execute($params);
}

delete_image();

?>