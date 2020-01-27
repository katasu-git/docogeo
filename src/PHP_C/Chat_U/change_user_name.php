<?php

require_once("../connect_mysql.php");

function change_user_name() {
    $id = $_POST['id'];
    $user_name_changed = $_POST['user_name_changed'];

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE active_user SET name = :user_name_changed WHERE id = $id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':user_name_changed' => $user_name_changed);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

change_user_name();

?>