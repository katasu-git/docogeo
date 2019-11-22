<?php

require_once("../connect_mysql.php");

function get_spot_image() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];

    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM images WHERE tour_id=$tour_id AND spot_id=$spot_id AND isDeleted=0 ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'imgName' => $row['image_name'], 'imgPath' => $row['image_path']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(get_spot_image());

?>