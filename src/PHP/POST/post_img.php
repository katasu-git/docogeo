<?php

require_once("../connect_mysql.php");

function post_image() {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $img_id = $_POST['img_id'];
    $img_path = $_POST['img_path'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    dist_post (tour_id, spot_id, img_id, img_path) 
    VALUES (:tour_id, :spot_id, :img_id, :img_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':img_id', $img_id, PDO::PARAM_INT);
    $stmt->bindParam(':img_path', $img_path, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

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

post_image();

isPosted_img_t();

?>