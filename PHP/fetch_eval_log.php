<?php
ini_set('display_errors',1);
require_once("./connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function fetch_eval_log() {
    $tour_id = $_POST['tour_id'];

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM location WHERE tour_id=$tour_id ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    $i = 0;
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'altitude' => $row['altitude']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(fetch_eval_log());

?>