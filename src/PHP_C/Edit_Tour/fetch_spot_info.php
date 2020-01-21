<?php

require_once("../connect_mysql.php");

function fetch_spot_info() {
    $tour_id = $_POST['tour_id'];
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM spot_name WHERE tour_id=$tour_id AND isDeleted=0 ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $spot_info = array('spot_id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_name' => $row['spot_name'], 'updated' => $row['updated']);
        array_push($result, $spot_info);
    }
    return $result;
}
echo json_encode(fetch_spot_info());

?>