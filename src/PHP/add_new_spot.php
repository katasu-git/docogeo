<?php

require_once("./connect_mysql.php");

function innsert_spot_ex() {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $spot_name = $_POST['spot_name'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    spot_name (tour_id, spot_name, updated) 
    VALUES (:tour_id, :spot_name, :updated)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindParam(':spot_name', $spot_name, PDO::PARAM_STR);
    $stmt->bindParam(':updated', $date);

    $stmt->execute();
    echo "成功！";

}

innsert_spot_ex();

?>