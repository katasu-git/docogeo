<?php

require_once("../connect_mysql.php");

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

function reset_user_name_isActive() {
    $id = $_POST['id'];

    $pdo = connect_mysql();  //mysqlに接続
    
    $sql = "UPDATE user_name SET isActive = :isActive WHERE id=$id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isActive' => 0);
    $stmt->execute($params);
}

function delete_active_user() {
    $id = $_POST['id'];

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE active_user SET isDeleted = :isDeleted WHERE user_name_id=$id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1);

    $stmt->execute($params);
}

finish_tour();

reset_user_name_isActive();

delete_active_user();