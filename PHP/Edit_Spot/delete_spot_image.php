<?php

require_once("../connect_mysql.php");

function delete_spot_ex() {
    $id = $_POST['id'];
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "UPDATE spot_image SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1, 'deleted' => $date);
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

delete_spot_ex();

?>