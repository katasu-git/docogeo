<?php
ini_set('display_errors',1);
require_once("../connect_mysql.php");
header("Access-Control-Allow-Origin: *"); //CORS回避

function add_img_db($data) {

    //パスを用意//////////////////////////////////////
    $dir = str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz');
    $path = "../../pic/" . $dir . ".png";

    $canvas = $data;
    $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
    $canvas = base64url_decode($canvas);
    $image = imagecreatefromstring($canvas);
    imagesavealpha($image, TRUE); // 透明色の有効
    imagepng($image, $path);

    $path = "/~nishimura/docogeo/pic/" . $dir . ".png";

    /////////////////////////////////////////////////

    //spotに画像を追加////////////////////////////////////

    $tour_id = $_POST['tour_id'];
    $spot_id = $_POST['spot_id'];
    $image_path = $path;

    $pdo = connect_mysql();  //mysqlに接続

    //スポットに追加//////////////////////////////////////////
    $stmt = $pdo -> prepare("INSERT INTO spot_image (tour_id, spot_id, image_path, isPosted) VALUES (:tour_id, :spot_id, :image_path, :isPosted)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $stmt->bindValue(':isPosted', 1, PDO::PARAM_INT); //即時投稿されるので
    $stmt->execute();

    //spot_image_idを取得////////////////////////////////////

    $sql = "SELECT * FROM spot_image WHERE tour_id=$tour_id AND spot_id=$spot_id AND isDeleted=0 ORDER BY id DESC";
    $stmt = $pdo -> query($sql);
    $result = array();
    foreach($stmt as $row) {
        //帰り値の設定
        $image = array('id' => $row['id'], 'tour_id' => $row['tour_id'], 'spot_id' => $row['spot_id'], 'image_id' => $row['image_id'], 'image_path' => $row['image_path'], 'isPosted' => $row['isPosted'] ,'likes' => $row['likes']);
        array_push($result, $image);
        break;
    }
    $spot_image_id = $result[0]["id"];

    //dist_postに登録
    $stmt = $pdo -> prepare("INSERT INTO dist_post (tour_id, spot_id, spot_image_id, image_path) VALUES (:tour_id, :spot_id, :spot_image_id, :image_path)");
    $stmt->bindValue(':tour_id', $tour_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    $stmt->bindValue(':spot_image_id', $spot_image_id, PDO::PARAM_INT);
    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
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