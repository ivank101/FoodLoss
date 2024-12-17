<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定</title>
    <link rel="stylesheet" href="css/settings.css">
</head>

<body>
    <div class="settings-container">
        <h2>設定</h2>

        <!-- 言語 -->
        <div class="setting-item">
            <label for="language">言語：</label>
            <select id="language">
                <option value="ja">日本語（Japanese）</option>
                <option value="en">English</option>
                <option value="vi">Tiếng Việt</option>
            </select>
        </div>

        <!-- パスワードの変更 -->
        <div class="setting-item">
            <label for="currentPassword">現在のパスワード：</label>
            <input type="password" id="currentPassword" placeholder="現在のパスワードを入力">
            <label for="newPassword">新しいパスワード：</label>
            <input type="password" id="newPassword" placeholder="新しいパスワードを入力">
            <label for="confirmNewPassword">新しいパスワードの確認：</label>
            <input type="password" id="confirmNewPassword" placeholder="新しいパスワードを再入力">
            <button onclick="changePassword()">パスワードを変更</button>
        </div>

        <!-- セキュリティ -->
        <div class="setting-item">
            <label for="enable2FA">二要素認証を有効にする：</label>
            <input type="checkbox" id="enable2FA">
            <button onclick="enable2FA()">保存する</button>
        </div>

        <!-- 設定を保存するボタン -->
        <button class="save-settings" onclick="saveSettings()">設定を保存する</button>
    </div>

    <script src="js/settingsScript.js"></script>
</body>

</html>
