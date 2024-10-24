<?php
//////////////////////////
// ツイートデータを処理
/////////////////////////

/**
 * ツイート作成
 *
 * @param array $data
 * @return bool
 */
function createTweet(array $data) {

    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // 接続エラーがある場合->処理停止
    if ($mysqli->connect_errno) { // エラーがある場合エラーコードを返す
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "/n";
        exit;
    }

    // 新規登録のSQLクエリを作成
    $query = 'INSERT INTO tweets (user_id, body, image_name) VALUES (?, ?, ?)';

    // プロペアドステートメントにクエリを登録
    $statement = $mysqli->prepare($query);

    // プレースフォルダにカラム値を紐付け（i=int, s=string)
    $statement->bind_param('iss', $data['user_id'], $data['body'], $data['image_name']);

    // クエリを実行
    $response = $statement->execute();
    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

    // DB接続を開放
    $statement->close();
    $mysqli->close();

    return $response;
}