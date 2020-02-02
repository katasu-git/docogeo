<?php

require_once("../connect_mysql.php");

function get_spot_image() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];

    $pdo = connect_mysql();  //mysqlに接続
    
    $sql = "SELECT * FROM spot_image WHERE tour_id=$tour_id AND spot_id=$spot_id AND isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_id' => $row['spot_id'], 'image_id' => $row['image_id'], 'image_path' => $row['image_path'], 'isPosted' => $row['isPosted']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(get_spot_image());

?>