<?php

require_once("./connect_mysql.php");

function add_img_spot() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_id = $_POST['image_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE images SET tour_id = :tour_id, spot_id = :spot_id, updated = :updated WHERE id = :id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array('tour_id' => $tour_id, ':spot_id' => $spot_id, ':id' => $image_id, ':updated' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);
 
    // 更新完了のメッセージ
    //echo '更新完了しました';

}

add_img_spot();

?>