<?php

require_once("./connect_mysql.php");

function update_tour_date($tour_id) {
    $pdo = connect_mysql();  //mysqlに接続
    $date = date("Y/m/d H:i:s");
        
    $sql = "UPDATE tour_name SET updated = :updated WHERE id = $tour_id";

    $stmt = $pdo->prepare($sql);

    $params = array(':updated' => $date);

    $stmt->execute($params);
}
echo json_encode(update_tour_date()); 

?>
