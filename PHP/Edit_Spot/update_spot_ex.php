<?php

require_once("../connect_mysql.php");

function update_spot_ex() {
    $id = $_POST['id'];
    $selected_changed = $_POST['selected_changed'];
    $date = date("Y/m/d H:i:s");

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "UPDATE spot_explanation SET spot_explanation = :spot_explanation, updated = :updated WHERE id = $id";
    $stmt = $pdo->prepare($sql);
    $params = array(':spot_explanation' => $selected_changed, 'updated' => $date);
    $stmt->execute($params);

}

update_spot_ex();

?>