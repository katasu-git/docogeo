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

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    images (image_path) 
    VALUES (:image_path)");
    $stmt->bindParam(':image_path', $path, PDO::PARAM_STR);

    $stmt->execute();

    return $img_path;

}

function base64url_decode($data) { 
    // = 埋めしなくてもいける
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

//撮影した画像を配信する
function post_image($image_path) {
    //必要な引数を用意
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    dist_post (tour_id, spot_id, img_id, img_path) 
    VALUES (:tour_id, :spot_id, :img_id, :img_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindParam(':img_path', $img_path, PDO::PARAM_STR);

    $stmt->execute();
    echo "成功！";

}

try {
    add_img_db($_POST['canvasData']);
    //post_image($image_path);
    return "成功";
} catch(PDOException $e) {
    echo $e;
    return $e;
}

?>