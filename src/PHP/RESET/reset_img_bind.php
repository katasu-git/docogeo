<?php

require_once("../connect_mysql.php");

function reset_img_bind() {
    $img_id = $_POST['img_id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE images SET tour_id = :tour_id, spot_id = :spot_id, isAdded = :isAdded, updated = :updated WHERE id = $img_id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':tour_id' => 0, ':spot_id' => 0, ':isAdded' => 0, ':updated' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

reset_img_bind();

?>