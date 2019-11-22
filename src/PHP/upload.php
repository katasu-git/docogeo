<?php
header("Access-Control-Allow-Origin: *"); //CORS回避
require_once("./connect_mysql.php");

function add_img_db() {

    $dir = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    $date = date("Y/m/d H:i:s");
    //ファイルをフォルダーに移動
    //move_uploaded_file($_FILES["upfile"]["tmp_name"], "../../docogeo/pic/" . $_FILES["upfile"]["name"]);
    move_uploaded_file($_FILES["upfile"]["tmp_name"], "../../docogeo/pic/" . $dir);

    $image_name = '画像の名前';
    $image_path = "/~nishimura/docogeo/pic/" . $dir;

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    images (image_name, image_path, uploaded, updated) 
    VALUES (:image_name, :image_path, :uploaded, :updated)");
    $stmt->bindValue(':image_name', $image_name, PDO::PARAM_STR);
    $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->bindParam(':uploaded', $date, PDO::PARAM_STR);
    $stmt->bindParam(':updated', $date, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

add_img_db();

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