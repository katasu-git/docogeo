<?php

require_once("./connect_mysql.php");

function update_tour_name() {
    $tour_id = $_POST['tour_id'];
    $tour_name_updated = $_POST['tour_name_updated'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE tour_name SET tour_name = :tour_name, updated = :updated WHERE id = :id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':tour_name' => $tour_name_updated, ':id' => $tour_id, 'updated' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

update_tour_name();

?>