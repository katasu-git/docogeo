<?php

require_once("../connect_mysql.php");

function get_spot_ex() {
    $spot_id = $_POST['spot_id'];
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM spot_explanation WHERE spot_id=$spot_id AND isDeleted=0 ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $spot_ex = array('id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_name' => $row['spot_name'], 'spot_ex' => $row['spot_explanation'], 'created' => $row['created']);
        array_push($result, $spot_ex);
    }
    return $result;
}
echo json_encode(get_spot_ex());

?>