<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function fetch_user_name() {
    $pdo = connect_mysql();  //mysqlに接続

    $sql = "SELECT * FROM user_name WHERE isActive=0"; //使われていないユーザ名を持ってくる
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_name_arr = array('user_name_id' => $row['id'], 'init_name' => $row['init_name'], 'comment' => $row['comment'], 'isActive' => $row['isActive']);
        array_push($result, $user_name_arr);
    }
    return $result;
}
echo json_encode(fetch_user_name()); 

?>
