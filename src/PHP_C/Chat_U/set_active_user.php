<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function set_active_user() {
    $user_name_id = $_POST['user_name_id'];
    $tour_id = $_POST['tour_id'];
    $init_name = $_POST['init_name'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    active_user (tour_id, user_name_id, init_name, name) 
    VALUES (:tour_id, :user_name_id, :init_name, :name)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_name_id', $user_name_id, PDO::PARAM_INT);
    $stmt->bindValue(':init_name', $init_name);
    $stmt->bindValue(':name', $init_name);

    $stmt->execute();

}

function set_isActive() {
    $user_name_id = $_POST['user_name_id'];

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "UPDATE user_name SET isActive=:isActive WHERE id=$user_name_id";
    $stmt = $pdo->prepare($sql);
    $params = array('isActive' => 1);
    $stmt->execute($params);
}

function fetch_user_info() {
    $id = $_POST['user_name_id'];

    $pdo = connect_mysql();  //mysqlに接続

    $sql = "SELECT * FROM active_user WHERE isDeleted=0 AND user_name_id=$id"; //使われていないユーザ名を持ってくる
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_info = array('id' => $row['id'], 'init_name' => $row['init_name'], 'name' => $row['name'], 'user_name_id' => $row['user_name_id'], 'created' => $row['created']);
        array_push($result, $user_info);
    }
    return $result;
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
echo json_encode(fetch_user_info()); //JSON_ENCODEは必要
//test();

?>