<?php

require_once("./connect_mysql.php");

function get_spot_id_array() {
    $tour_id = $_POST['tour_id'];
    $pdo = connect_mysql();  //mysqlに接続
    //本番環境ではisPpsted=1を取得
    $sql = "SELECT * FROM spot_name WHERE tour_id=$tour_id ORDER BY id ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $spot_info = array('spot_id' => $row['id'], 'spot_name' => $row['spot_name']);
        array_push($result, $spot_info);
    }
    return $result;
}
echo json_encode(get_spot_id_array());

?>