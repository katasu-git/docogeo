<?php

require_once("./connect_mysql.php");

function update_order_spot_ex() {
    $spot_id_arr = $_POST['spot_id_arr'];
    $order = $_POST['order'];
    
    //for($i=0;$i<=count($spot_id_arr);$i++) {
        $pdo = connect_mysql();  //mysqlに接続
        
        $sql = "UPDATE spot_explanation SET order_num = :order_num WHERE id = :id";
    
        $stmt = $pdo->prepare($sql);
    
        $params = array(':order_num' => $order, ':id' => $spot_id_arr);
    
        $stmt->execute($params);
    //}

    $result = $spot_id_arr;
    echo $result;
    return $result;

}

update_order_spot_ex();

?>