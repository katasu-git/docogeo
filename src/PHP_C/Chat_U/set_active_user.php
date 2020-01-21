<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function set_active_user() {
    $id = $_POST['id'];
    $tour_id = $_POST['tour_id'];
    $init_name = $_POST['init_name'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    active_user (tour_id, user_name_id, init_name, name) 
    VALUES (:tour_id, :user_name_id, :init_name, :name)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_name_id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':init_name', $init_name);
    $stmt->bindValue(':name', $init_name);

    $stmt->execute();

}

function set_isActive() {
    $id = $_POST['id'];

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE user_name SET isActive = :isActive, start_using = :start_using WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $params = array(':id' => $id, 'isActive' => 1, 'start_using' => $date);
    $stmt->execute($params);
}

function test() {
    $id = $_POST['id'];
    $tour_id = $_POST['tour_id'];
    $init_name = $_POST['init_name'];
    $result = $id . $tour_id . $init_name;
    echo $result;
    return $result;

}

set_active_user();
set_isActive();
//test();

?>