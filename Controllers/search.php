<?php
///////////////////////////
// サーチコントローラー
///////////////////////////

// 設定を読み込み
include_once '../config.php';
// 便利な関数を読み込み
include_once '../util.php';

// ツイートデータ操作モデルを読み込み
include_once '../Models/tweets.php';

//ログインチェック
$user = getUserSession();
if (!$user) {
    // ログインしていない
    header('Location: ' . HOME_URL . 'Controllers/sign-in.php');
    exit;
}

// 検索キーワードを取得
$keyword = null; // $keywordを初期化
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword']; // GET変数でkeywordがあれば変数に代入
}

// 表示用の変数
$view_user = $user;
$view_keyword = $keyword;
// ツイート一覧
$view_tweets = findTweets($user, $keyword);

// 画面表示
include_once '../Views/search.php';