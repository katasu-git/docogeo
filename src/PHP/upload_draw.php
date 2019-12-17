<?php
header("Access-Control-Allow-Origin: *"); //CORS回避
require_once("./connect_mysql.php");

function add_img_db($data) {

    $dir = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    $path = "../../docogeo/pic_draw/" . $dir;

    $canvas = $data;
    $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
    $canvas = base64url_decode($canvas);
    $image = imagecreatefromstring($canvas);
    imagesavealpha($image, TRUE); // 透明色の有効
    imagepng($image, $path);


    $path = "/~nishimura/docogeo/pic_draw/" . $dir;

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    draw (image_path) 
    VALUES (:image_path)");
    $stmt->bindParam(':image_path', $path, PDO::PARAM_STR);

    $stmt->execute();

}

function base64url_decode($data) { 
    // = 埋めしなくてもいける
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 

try {
    add_img_db($_POST['canvasData']); //偶数が線画像
    //add_img_db($_POST['backImage']); //奇数が背景画像
    return "成功";
} catch(PDOException $e) {
    echo $e;
    return $e;
}

?>