<?php

require_once("../connect_mysql.php");

function fetch_user_name() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM user_name WHERE isActive=0"; //使われていないユーザ名を持ってくる
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_name_arr = array('user_name_id' => $row['id'], 'init_name' => $row['init_name'], 'isActive' => $row['isActive'], 'start_using' => $row['start_using']);
        array_push($result, $user_name_arr);
    }
    return $result;
}
echo json_encode(fetch_user_name()); 

?>
