<?php

require_once("../connect_mysql.php");

function update_spot_ex() {
    $id = $_POST['id'];
    $selected_changed = $_POST['selected_changed'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE spot_explanation SET spot_explanation = :spot_explanation, updated = :updated WHERE id = $id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':spot_explanation' => $selected_changed, 'updated' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

update_spot_ex();

?>