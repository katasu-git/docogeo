<?php

require_once("../connect_mysql.php");

function chage_spot_name() {
    $spot_id = $_POST['spot_id'];
    $spot_name_changed = $_POST['spot_name_changed'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE spot_name SET spot_name = :spot_name, updated = :updated WHERE id = :id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':spot_name' => $spot_name_changed, ':id' => $spot_id, 'updated' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

chage_spot_name();

?>