<?php
header("Access-Control-Allow-Origin: *"); //CORS回避
require_once("./connect_mysql.php");

function innsert_image_info() {
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    spot_explanation (tour_id, spot_id, spot_explanation) 
    VALUES (:tour_id, :spot_id, :spot_explanation)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindParam(':spot_explanation', $spot_ex, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

//ファイルをフォルダーに移動
move_uploaded_file($_FILES["upfile"]["tmp_name"], "../../docogeo/pic/" . $_FILES["upfile"]["name"]);

//DBに登録
//innsert_image_info();

/*

$_FILES['inputで指定したname']['name']：ファイル名
$_FILES['inputで指定したname']['type']：ファイルのMIMEタイプ
$_FILES['inputで指定したname']['tmp_name']：一時保存ファイル名
$_FILES['inputで指定したname']['error']：アップロード時のエラーコード
$_FILES['inputで指定したname']['size']：ファイルサイズ（バイト単位）

*/

$result = $_FILES['upfile']['name'];
echo $result;
return $result;
?>