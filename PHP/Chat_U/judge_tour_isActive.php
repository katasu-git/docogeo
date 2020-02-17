<?php

require_once("../connect_mysql.php");

function jusge_tour_isActive() {
    $pdo = connect_mysql();  //mysqlに接続
    $tour_id = $_POST['tour_id'];
    $sql = "SELECT * FROM tour_name WHERE isDeleted=0 AND id=$tour_id ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $tour_info = array('tour_id' => $row['id'], 'tour_name' => $row['tour_name'], 'isActive' => $row['isActive'], 'updated' => $row['updated']);
        array_push($result, $tour_info);
    }
    return $result;
}
echo json_encode(jusge_tour_isActive()); 

?>
