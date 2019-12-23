<?php

require_once("./connect_mysql.php");

function isPosted_img_t() {
    $id = $_POST['id'];
    try {
        $pdo = connect_mysql();  //mysqlに接続
    
        // UPDATE文を変数に格納
        $sql = "UPDATE spot_image SET isPosted = :isPosted WHERE id = $id";
    
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

function delete_posted_post() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_id = $_POST['image_id'];
    $date = date("Y/m/d H:i:s");
    try {

        $pdo = connect_mysql();  //mysqlに接続

        $sql = "UPDATE dist_post SET isDeleted = :isDeleted, deleted = :deleted WHERE tour_id = $tour_id AND spot_id = $spot_id AND img_id = $image_id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':isDeleted' => 1, ':deleted' => $date);
    
        $stmt->execute($params);

    } catch (PDOException $e) {
        echo $e;
    }
}

isPosted_img_t();

delete_posted_post();

?>