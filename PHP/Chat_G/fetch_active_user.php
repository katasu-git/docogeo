<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function fetch_active_user() {
    $tour_id = $_POST['tour_id'];
    
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM active_user WHERE tour_id=$tour_id AND isDeleted=0 ORDER BY id ASC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        $user_name = array('id' => $row['id'], 'name' => $row['name']);
        array_push($result, $user_name);
    }
    return $result;
}
echo json_encode(fetch_active_user()); 

?>
