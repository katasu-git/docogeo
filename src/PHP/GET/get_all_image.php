<?php

require_once("../connect_mysql.php");

function get_all_image() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM images WHERE isDeleted=0 ORDER BY order_num ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'imgName' => $row['image_name'], 'imgPath' => $row['image_path']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(get_all_image());

?>