<?php

require_once("./connect_mysql.php");

function get_post() {
    $tour_id = $_POST['tour_id'];
    $pdo = connect_mysql();  //mysqlに接続
    //本番環境ではisPpsted=1を取得
    $sql = "SELECT * FROM dist_post WHERE tour_id=$tour_id AND isDeleted=0 ORDER BY id ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $spot_ex = array('ex_id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_id' => $row['spot_id'], 'spot_ex' => $row['posted_ex'], 'img_flag' => $row['img_flag'], 'imgPath' => $row['img_path']);
        array_push($result, $spot_ex);
    }
    return $result;
}
echo json_encode(get_post());

?>