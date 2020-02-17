<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function get_all_image() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM images WHERE isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'image_path' => $row['image_path'], 'isAdded' => $row['isAdded']);
        array_push($result, $image);
    }
    return $result;
}
echo json_encode(get_all_image());

?>