<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function get_all_image() {
    $tour_id = $_POST['tour_id'];

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM trans_image WHERE isDeleted=0 AND tour_id = $tour_id ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'image_path' => $row['image_path'], 'opacity' => $row['opacity']);
        array_push($result, $image);
    }
    return $result[0];
}
echo json_encode(get_all_image());

?>