<?php

require_once("./connect_mysql.php");

function innsert_spot_ex() {
    //必要な引数を用意
    $tour_name = $_POST['tour_name'];
    $group_name = $_POST['group_name'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    tour_name (tour_name, group_name) 
    VALUES (:tour_name, :group_name)");
    $stmt->bindValue(':tour_name', $tour_name, PDO::PARAM_INT);
    $stmt->bindParam(':group_name', $group_name, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

innsert_spot_ex();

?>