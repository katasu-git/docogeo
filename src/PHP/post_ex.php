<?php

require_once("./connect_mysql.php");

function post_ex() {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $ex_id = $_POST['ex_id'];
    $posted_ex = $_POST['posted_ex'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    dist_post (tour_id, spot_id, ex_id, posted_ex) 
    VALUES (:tour_id, :spot_id, :ex_id, :posted_ex)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':ex_id', $ex_id, PDO::PARAM_INT);
    $stmt->bindParam(':posted_ex', $posted_ex, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

post_ex();

?>