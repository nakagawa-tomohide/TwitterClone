<?php
/////////////////////////////////////
//便利な関数
////////////////////////////////////
/**
 * 画像ファイルから画像のURLを生成する
 *
 * @param string $name 画像ファイル名
 * @param string $type user | tweet
 * @return string
 */
function buildImagePath(string $name = null, string $type)
{
    if($type === 'user' && !isset($name)) { //'tweet_user_name'がnullでuserを指定するとデフォルトのアイコン画像が表示
        return HOME_URL . 'Views/img/icon-default-user.svg';
    }

    return HOME_URL . 'Views/img_uploaded/' . $type . '/' . htmlspecialchars($name);
}

/**
 * 指定した日時からどれだけ経過したかを取得
 *
 * @param string $datetime 日時
 * @return string
 */
function convertToDayTimeAgo(string $datetime) //stringはタイプヒンティング(型宣言)
{
    $unix = strtotime($datetime);
    $now = time();
    $diff_sec = $now - $unix; //今の時間から投稿日時を引いて経過時間を変数に代入

    if($diff_sec < 60) { //１分未満(60秒より少ないと)場合秒数で返す
        $time = $diff_sec;
        $unit = '秒前';
    } elseif($diff_sec < 3600) { //１時間未満の場合分数で返す
        $time = $diff_sec / 60;
        $unit = '分前';
    } elseif($diff_sec < 86400) { //1日未満の場合時間で返す
        $time = $diff_sec / 3600;
        $unit = '時間前';
    } elseif($diff_sec < 2764800) { //32日未満場合日で返す
        $time = $diff_sec / 86400;
        $unit = '日前';
    } else {

        if(date('Y') !== date('Y', $unix)) { //現在の年と投稿日時の年が違うかどうか確認(第二引数で投稿日時を入れる)
            $time = date('Y年n月j日', $unix); //違うと年月日の形で返す
        } else {
            $time = date('n月j日', $unix); //同じだと月日の形で返す
        }
        return $time;
    }

    return (int)$time . $unit; //(int)は型キャスト intで表せれない場合は0になり、小数点以下は切り捨てられる
}
/**
 * ユーザー情報をセッションに保存
 *
 * @param array $user
 * @return void
 */
function saveUserSession(array $user) {
    // セッションを開始していない場合（セッションを開始していない場合PHP_SESSION_NONEが戻る）
    if (session_status() === PHP_SESSION_NONE) {
        // セッションを開始
        session_start();
    }

    $_SESSION['USER'] = $user;
}

/**
 * ユーザー情報をセッションから削除
 *
 * @return void
 */
function deleteUserSession() {
    // セッションを開始していない場合
    if (session_status() === PHP_SESSION_NONE) {
        // セッション開始
        session_start();
    }

    // セッションのユーザー情報を削除
    unset($_SESSION['USER']);
}

/**
 * セッションのユーザー情報を取得
 *
 * @return array|false
 */
function getUserSession() {
    // セッションを開始していない場合
    if (session_status() === PHP_SESSION_NONE) {
        // セッション開始
        session_start();
    }

    if (!isset($_SESSION['USER'])) {
        // セッションにユーザー情報がない
        return false;
    }

    $user = $_SESSION['USER'];

    // 画像のファイル名からファイルのURLを取得
    if (!isset($user['image_name'])) {
        $user['image_name'] = null;
    }
    $user['image_path'] = buildImagePath($user['image_name'], 'user');

    return $user;
}