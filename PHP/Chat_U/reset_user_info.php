<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function reset_user_name_isActive() {
    $user_name_id = $_POST['user_name_id'];

    $pdo = connect_mysql();  //mysqlに接続
    
    $sql = "UPDATE user_name SET isActive = :isActive WHERE id=$user_name_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isActive' => 0);
    $stmt->execute($params);
}

function delete_active_user() {
    $id = $_POST['id'];

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE active_user SET isDeleted = :isDeleted WHERE id=$id";
    $stmt = $pdo->prepare($sql);
    $params = array(':isDeleted' => 1);

    $stmt->execute($params);
}

reset_user_name_isActive();

delete_active_user();