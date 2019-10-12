<?php

require_once("./connect_mysql.php");

function get_tour_info() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM tour_name";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $tour_info = array('tour_name' => $row['tour_name'], 'group_name' => $row['group_name']);
        array_push($result, $tour_info);
    }
    return $result;
}
echo json_encode(get_tour_info()); 

?>
