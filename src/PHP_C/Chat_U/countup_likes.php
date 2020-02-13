<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function countup_pageview() {
    $pdo = connect_mysql();  //mysqlに接続

    $dist_id = $_POST['dist_id']; //distの主キー
    $ex_id = $_POST['ex_id']; //spot_explanationの主キー
    $img_id = $_POST['img_id'];
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $img_flag = $_POST['img_flag'];

    $sql = "UPDATE dist_post SET likes = likes + 1 WHERE id = $dist_id";
    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => 1);
    $stmt->execute($params);

    if($img_flag == "true") {
        $sql = "UPDATE spot_image SET likes = likes + 1 WHERE image_id = $img_id AND tour_id = $tour_id AND spot_id = $spot_id"; 
    } else {
        $sql = "UPDATE spot_explanation SET likes = likes + 1 WHERE id = $ex_id";
    }

    $stmt = $pdo->prepare($sql);
    $params = array(':updated' => 1);
    $stmt->execute($params);
}
echo json_encode(countup_pageview()); 

?>
