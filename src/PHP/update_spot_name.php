<?php

require_once("./connect_mysql.php");

function innsert_spot_ex() {
    $spot_id = $_POST['spot_id'];
    $spot_name_updated = $_POST['spot_name_updated'];

    $pdo = connect_mysql();  //mysqlに接続

    // UPDATE文を変数に格納
    $sql = "UPDATE spot_name SET spot_name = :spot_name WHERE id = :id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':spot_name' => $spot_name_updated, ':id' => $spot_id);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);
 
    // 更新完了のメッセージ
    //echo '更新完了しました';

}

innsert_spot_ex();

?>