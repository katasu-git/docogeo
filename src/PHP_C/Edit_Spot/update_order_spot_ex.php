<?php

require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避
header('Access-Control-Allow-Headers: Content-Type');

function update_order_spot_ex() {

    $spot_ex = file_get_contents('php://input');//JSON受け取るおまじない
    $spot_ex = json_decode($spot_ex, true);//JSONを扱えるようにデコード

    for($i=0; $i<count($spot_ex); $i++) {
        $pdo = connect_mysql();  //mysqlに接続
    
        $sql = "UPDATE spot_explanation SET order_num = :order_num WHERE id = :id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':order_num' => $i, ':id' => $spot_ex[$i]['id']);
    
        $stmt->execute($params);
    }

}

update_order_spot_ex();

?>