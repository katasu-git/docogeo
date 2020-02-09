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

    //drawにインサートする
    $pdo = connect_mysql();  //mysqlに接続
    $opacity = $_POST['opacity'];

    $stmt = $pdo -> prepare("INSERT INTO 
    draw (image_path, opacity) 
    VALUES (:image_path, :opacity)");
    $stmt->bindParam(':image_path', $path, PDO::PARAM_STR);
    $stmt->bindParam(':opacity', $opacity, PDO::PARAM_INT);

    $stmt->execute();


    //images にインサートする
    $pdo = connect_mysql();  //mysqlに接続

    $stmt = $pdo -> prepare("INSERT INTO 
    images (image_path) 
    VALUES (:image_path)");
    $stmt->bindParam(':image_path', $path, PDO::PARAM_STR);

    $stmt->execute();
}

function base64url_decode($data) { 
    // = 埋めしなくてもいける
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

function get_all_image() {
    $pdo = connect_mysql();  //mysqlに接続
    $sql = "SELECT * FROM images WHERE isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'image_path' => $row['image_path']);
        array_push($result, $image);
    }
    return $result;
}

try {
    add_img_db($_POST['canvasData']);
    echo json_encode(get_all_image());
} catch(PDOException $e) {
    echo $e;
    return $e;
}

?>