<?php

require_once("./connect_mysql.php");

function finish_tour() {
    $tour_id = $_POST['tour_id'];
    try {
        $pdo = connect_mysql();  //mysqlに接続

        //$stmt = $pdo->prepare('DELETE FROM dist_post WHERE tour_id = :tour_id');

        //$stmt->execute(array(':tour_id' => $tour_id));

        $stmt = $pdo->prepare( 'DELETE FROM dist_post WHERE tour_id = :tour_id' );
        $stmt->bindParam( ':tour_id', $tour_id, PDO::PARAM_INT );
        $stmt->execute();
        $pdo->commit();

        echo "成功！";

    } catch (PDOException $e) {
        echo $e;
    }
}

finish_tour();

?>