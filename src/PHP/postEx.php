<?php

require_once("./connect_mysql.php");

function postEx() {
    $ex_id = $_POST['ex_id'];
    $pdo = connect_mysql();  //mysqlに接続
    
    // UPDATE文を変数に格納
    $sql = "UPDATE spot_explanation SET isPosted = :isPosted WHERE id = :id";
 
    // 更新する値と該当のIDは空のまま、SQL実行の準備をする
    $stmt = $pdo->prepare($sql);
 
    // 更新する値と該当のIDを配列に格納する
    $params = array(':isPosted' => 1, ':id' => $ex_id);
 
    // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
    $stmt->execute($params);
}
echo json_encode(postEx());

?>