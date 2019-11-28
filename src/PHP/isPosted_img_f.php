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
        $params = array(':isPosted' => 0, ':id' => $img_id);
    
        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);
        //return $isPosted;
        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

function delete_posted_post() {
    $img_id = $_POST['img_id'];
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted WHERE img_id = :img_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':ex_id' => $ex_id, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

isPosted_img_t();

delete_posted_post();

?>