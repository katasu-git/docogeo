<?php

require_once("../connect_mysql.php");

function get_tour_start_time() {
    $tour_id = $_POST['tour_id'];
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM tour_name WHERE isDeleted=0 AND id=$tour_id";
    $stmt = $pdo -> query($sql);
    $result = '';
    foreach($stmt as $row) {
        //帰り値の設定
        $result = array('id' => $row['id'], 'tour_name' => $row['tour_name'], 'isActive' => $row['isActive'], 'start_time' => $row['start_time']);
    }
    return $result;
}
echo json_encode(get_tour_start_time());

?>