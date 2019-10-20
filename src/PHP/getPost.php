<?php

require_once("./connect_mysql.php");

function get_post() {
    $spot_id = $_POST['spot_id'];
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM spot_explanation WHERE spot_id=$spot_id AND isPosted=0";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $spot_ex = array('spot_ex' => $row['spot_explanation']);
        array_push($result, $spot_ex);
    }
    return $result;
}
echo json_encode(get_post());

?>