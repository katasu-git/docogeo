<?php

require_once("../connect_mysql.php");

function get_all_image() {
    $tour_id = $_POST['tour_id'];
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM images WHERE isDeleted=0 AND tour_id=$tour_id ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = '';
    foreach($stmt as $row) {
        //帰り値の設定
        $result = array('id' => $row['id'], 'imgPath' => $row['image_path']);
    }
    return $result;
}
echo json_encode(get_all_image());

?>