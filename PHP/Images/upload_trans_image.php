<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_db($data) {

    $dir = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    $path = "../../pic/" . $dir . ".png";

    $canvas = $data;
    $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
    $canvas = base64url_decode($canvas);
    $image = imagecreatefromstring($canvas);
    imagesavealpha($image, TRUE); // 透明色の有効
    imagepng($image, $path);

    $path = "/~nishimura/docogeo/pic/" . $dir . ".png";

    $pdo = connect_mysql();  //mysqlに接続

    //dist_postに登録
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $spot_image_id = $_POST['spot_image_id'];
    $image_path = $path;
    $opacity = $_POST['opacity'];;

    $stmt = $pdo -> prepare("INSERT INTO trans_image (tour_id, spot_id, spot_image_id, image_path, opacity) VALUES (:tour_id, :spot_id, :spot_image_id, :image_path, :opacity)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_image_id', $spot_image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->bindValue(':opacity', $opacity);
    $stmt->execute();
}

function base64url_decode($data) { 
    // = 埋めしなくてもいける
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

try {
    echo json_encode(add_img_db($_POST['image_data'])); //偶数が線画像
} catch(PDOException $e) {
    echo $e;
    return $e;
}

?>