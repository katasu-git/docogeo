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

function delete_all_posted_post() {
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

function reset_post() {
    try {
        $pdo = connect_mysql();  //mysqlに接続
    
        // UPDATE文を変数に格納
        $sql = "UPDATE spot_explanation SET isPosted = :isPosted";
    
        // 更新する値と該当のIDは空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
    
        // 更新する値と該当のIDを配列に格納する
        $params = array(':isPosted' => 0);
    
        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);
        //return $isPosted;
        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

function reset_post_img() {
    try {
        $pdo = connect_mysql();  //mysqlに接続
    
        // UPDATE文を変数に格納
        $sql = "UPDATE spot_image SET isPosted = :isPosted";
    
        // 更新する値と該当のIDは空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
    
        // 更新する値と該当のIDを配列に格納する
        $params = array(':isPosted' => 0);
    
        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);
        //return $isPosted;
        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

function reset_user_name_isActive() {
    $user_name_id = $_POST['user_name_id'];

    $pdo = connect_mysql();  //mysqlに接続
    
    $sql = "UPDATE user_name SET isActive = :isActive WHERE id=$user_name_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isActive' => 0);
    $stmt->execute($params);
}

//finish_tour();

//delete_all_posted_post();

//reset_post();

//reset_post_img();

reset_user_name_isActive();

?>