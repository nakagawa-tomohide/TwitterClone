<?php
///////////////////////////
// フォローコントローラー
///////////////////////////

// 設定を読み込み
include_once '../config.php';
// 便利な関数を読み込み
include_once '../util.php';
// フォローデータ操作モデルを読み込み
include_once '../Models/follows.php';
// 通知データ操作モデルを読み込み
include_once '../Models/notifications.php';

//ログインチェック
$user = getUserSession();
// ログインしていない場合
if (!$user) {
    // 404エラー
    header('HTTP/1.0 404 Not Found');
    exit;
}

// ---------------------------
// フォローする
// ---------------------------
$follow_id = null;
// followed_user_id がPOSTされた場合
if (isset($_POST['followed_user_id'])) {
    $data = [
        'followed_user_id' => $_POST['followed_user_id'], // フォローしたいユーザーのID
        'follow_user_id' => $user['id'],// 自分のID
    ];
    // フォロー登録
    $follow_id = createFollow($data); // 作成した新しいレコードのIDが返ってくる

    // 通知を登録
    $data_notification = [
        'received_user_id' => $_POST['followed_user_id'],
        'sent_user_id' => $user['id'],
        'message' => 'フォローされました。',
    ];
    createNotification($data_notification);
}

// ---------------------------
// フォロー解除
// ---------------------------
// follow_id がPOSTされた場合
if (isset($_POST['follow_id'])) {
    $data = [
        'follow_id' => $_POST['follow_id'], // 変更対象のフォローID
        'follow_user_id' => $user['id'], // 自分のID
    ];
    // フォロー解除
    deleteFollow($data);
}

// ---------------------------
// JSON形式で結果を返却
// ---------------------------
$response = [ // 返却したいデータを配列にまとめる
    'message' => 'successful',
    // フォローしたいときに値が入る
    'follow_id' => $follow_id,
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);