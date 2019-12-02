<?php

require_once("../connect_mysql.php");

function get_tour_img() {
    $pdo = connect_mysql();  //mysqlに接続
    $tour_id = $_POST['tour_id'];
    
    $sql = "SELECT * FROM images WHERE isDeleted=0 AND tour_id = $tour_id";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('imgPath' => $row['image_path']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(get_tour_img());

?>