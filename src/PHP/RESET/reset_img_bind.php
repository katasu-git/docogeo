<?php

require_once("../connect_mysql.php");

function reset_img_bind() {
    $id = $_POST['id'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE spot_image SET isDeleted = :isDeleted, deleted = :deleted WHERE id = $id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':isDeleted' => 1, ':deleted' => $date);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);

}

reset_img_bind();

?>