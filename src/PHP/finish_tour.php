<?php

require_once("./connect_mysql.php");

function finish_tour() {
    $tour_id = $_POST['tour_id'];
    try {
        $pdo = connect_mysql();  //mysqlに接続
    
        // UPDATE文を変数に格納
        $sql = "UPDATE tour_name SET isActive = :isActive WHERE id = :id";
    
        // 更新する値と該当のIDは空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
    
        // 更新する値と該当のIDを配列に格納する
        $params = array(':isActive' => 0, ':id' => $tour_id);
    
        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);
        //return $isPosted;
        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

finish_tour();

?>