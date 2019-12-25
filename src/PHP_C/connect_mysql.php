<?php

function connect_mysql() {

    try {
        /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */
        header("Access-Control-Allow-Origin: *"); //CORS回避
        // データベースに接続
        $pdo = new PDO(
          'mysql:dbname=nishimura;host=localhost;charset=utf8mb4',
          'nishimura',
          'TYoshino45!!',
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          ]
        );
        return $pdo;
      } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        return "失敗";
      }

}
