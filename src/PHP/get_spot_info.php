<?php

require_once("./connect_mysql.php");

function get_spot_info() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM spot_name";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $spot_info = array('spot_name' => $row['spot_name']);
        array_push($result, $spot_info);
    }
    return $result;
}
echo json_encode(get_spot_info());

?>