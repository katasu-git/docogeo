<?php

require_once("../connect_mysql.php");

function get_tour_info() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM tour_name WHERE isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $tour_info = array('tour_id' => $row['id'], 'tour_name' => $row['tour_name'], 'isActive' => $row['isActive'], 'updated' => $row['updated']);
        array_push($result, $tour_info);
    }
    return $result;
}
echo json_encode(get_tour_info()); 

?>
