<?php
ini_set('display_errors',1);
require_once("./connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function countup_pageview() {
    $pdo = connect_mysql();  //mysqlに接続
    $id = $_POST['id'];
    $where = $_POST['where'];

    if($where == 'userCamera') {
        $sql = "UPDATE active_user SET camera = camera + 1 WHERE id = $id";
    } else if($where == 'userChat') {
        $sql = "UPDATE active_user SET chat = chat + 1 WHERE id = $id";
    } else if($where == 'userMap') {
        $sql = "UPDATE active_user SET map = map + 1 WHERE id = $id";
    }

    $stmt = $pdo->prepare($sql);

    $params = array(':updated' => 1);

    $stmt->execute($params);
}
echo json_encode(countup_pageview());

?>
