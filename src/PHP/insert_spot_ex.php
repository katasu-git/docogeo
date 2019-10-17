<?php

require_once("./connect_mysql.php");

function innsert_spot_ex() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $spot_ex = $_POST['spot_ex'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    spot_explanation (tour_id, spot_id, spot_explanation) 
    VALUES (:tour_id, :spot_id, :spot_explanation)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindParam(':spot_explanation', $spot_ex, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

innsert_spot_ex();

?>