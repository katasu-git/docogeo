<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function update_order_spot_name() {

    $spot_info = $_POST['spot_info'];
    $spot_info = json_decode($spot_info, true);//JSONを扱えるようにデコード

    for($i=0; $i<count($spot_info); $i++) {
        $pdo = connect_mysql();  //mysqlに接続
        $sql = "UPDATE spot_name SET order_num = :order_num WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $params = array(':order_num' => $i, ':id' => $spot_info[$i]['spot_id']);
        $stmt->execute($params);
    }

}

update_order_spot_name();

?>