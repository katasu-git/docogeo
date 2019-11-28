<?php

require_once("./connect_mysql.php");

function isPosted_img_t() {
    $img_id = $_POST['img_id'];
    try {
        $pdo = connect_mysql();  //mysqlに接続
    
        // UPDATE文を変数に格納
        $sql = "UPDATE images SET isPosted = :isPosted WHERE id = :id";
    
        // 更新する値と該当のIDは空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
    
        // 更新する値と該当のIDを配列に格納する
        $params = array(':isPosted' => 1, ':id' => $img_id);
    
        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);
        //return $isPosted;
        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

isPosted_img_t();

?>