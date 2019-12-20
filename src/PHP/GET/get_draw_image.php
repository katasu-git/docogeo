<?php

require_once("../connect_mysql.php");

function get_draw_image() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM draw WHERE isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    $i = 0;
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'image_path' => $row['image_path']);
        array_push($result, $image);
        break;
    }
    return $result;
}
echo json_encode(get_draw_image());

?>