<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function get_post() {
    $spot_id = $_POST['spot_id'];
    
    $pdo = connect_mysql();

    $sql = "SELECT * FROM spot_explanation WHERE isDeleted=0 AND spot_id=$spot_id ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $spot_ex = array('ex_id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_id' => $row['spot_id'], 'spot_ex' => $row['spot_explanation'], 'likes' => $row['likes'], 'isPosted' => $row['isPosted'], 'sended' => $row['sended']);
        array_push($result, $spot_ex);
    }
    return $result;
}
echo json_encode(get_post());

?>