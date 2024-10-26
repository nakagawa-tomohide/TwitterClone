<?php
//////////////////////////
// いいね！データを処理
/////////////////////////

/**
 * いいね！を作成
 *
 * @param array $data
 * @return int|false
 */
function createLike(array $data) {
    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // 接続エラーがある場合->処理停止
    if ($mysqli->connect_errno) { // エラーがある場合エラーコードを返す
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
        exit;
    }

    // ---------------------------
    // SQLクエリを作成(登録)
    // ---------------------------
    $query = 'INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)';
    $statement = $mysqli->prepare($query);

    // プレースフォルダに値をセット
    $statement->bind_param('ii', $data['user_id'], $data['tweet_id']);

    // ---------------------------
    // 戻り値を作成
    // ---------------------------
    // クエリを実行し、SQLエラーでない場合
    if ($statement->execute()) {
        // 戻り値用の変数にセット：インサートID（likes.id）AUTO INCREMENT カラムで生成した値を返す
        $response = $mysqli->insert_id;
    } else {
        // 戻り値用の変数にセット：失敗
        $response = false;
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

    // ---------------------------
    // 後処理
    // ---------------------------
    //DBを開放
    $statement->close();
    $mysqli->close();

    return $response;
}

/**
 * いいね！取り消し
 *
 * @param array $data
 * @return bool
 */
function deleteLike(array $data) {
    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // 接続エラーがある場合->処理停止
    if ($mysqli->connect_errno) { // エラーがある場合エラーコードを返す
        echo 'MySQLの接続に失敗しました。:' . $mysqli->connect_error . "\n";
        exit;
    }
    // ---------------------------
    // SQLクエリを作成
    // ---------------------------
    // 論理削除のクエリを作成(deleteではなくupdateでstatusを書き換え見えなくする)
    $query = 'UPDATE likes SET status = "deleted" WHERE id = ? AND user_id = ?';
    $statement = $mysqli->prepare($query);

    // プレースホルダに値をセット
    $statement->bind_param('ii', $data['like_id'], $data['user_id']);

    // ---------------------------
    // 戻り値を作成
    // ---------------------------
    $response = $statement->execute();

    // SQLエラーの場合->エラー表示
    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }

    // ---------------------------
    // 後処理
    // ---------------------------
    // DB接続を開放
    $statement->close();
    $mysqli->close();

    return $response;
}