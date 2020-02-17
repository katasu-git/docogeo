<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_db($data) {

    $dir = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    $path = "../../pic/" . $dir . ".jpg"; 

    $canvas = $data;
    $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
    $canvas = base64url_decode($canvas);
    $image = imagecreatefromstring($canvas);
    //imagesavealpha($image, TRUE); // 透明色の有効
    imagejpeg($image, $path);

    $path = "/~nishimura/docogeo/pic/" . $dir . ".jpg";

    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO images (image_path) VALUES (:image_path)");
    $stmt->bindParam(':image_path', $path, PDO::PARAM_STR);
    //$stmt->bindValue(':isAdded', 1, PDO::PARAM_INT);
    $stmt->execute();


    //画像としてDBに登録
    $sql = "SELECT * FROM images WHERE isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'image_path' => $row['image_path'], 'isAdded' => $row['isAdded']);
        array_push($result, $image);
    }

    //スポットに登録
    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_id = $result[0]['id']; //34行目
    $image_path = $result[0]['image_path']; //34行目

    $stmt = $pdo -> prepare("INSERT INTO spot_image (tour_id, spot_id, image_id, image_path) VALUES (:tour_id, :spot_id, :image_id, :image_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->execute();

    /*
    if($_POST['camera_flag'] == "true") {
        //画像が追加されたという
        $sql = "UPDATE images SET isAdded = :isAdded WHERE id = $image_id";
        $stmt = $pdo->prepare($sql);
        $params = array(':isAdded' => 1);
        $stmt->execute($params);
    }
    */
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