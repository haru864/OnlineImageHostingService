<?php

use Settings\Settings;

$base_url = Settings::env("BASE_URL");
?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>画像アップロード</title>
    <link href="<?= $base_url ?>/css/uploader" rel="stylesheet" />
</head>

<body>
    <p>共有したい画像ファイルを選択してアップロードしてください。</p>
    <p>JPEG,GIF,PNGファイルのいずれかをアップロードできます。</p>
    <form id="uploadForm">
        <input type="file" id="fileUpload" name="fileUpload">
        <input type="button" value="アップロード" onclick="uploadFile()">
    </form>
    <div class="overlay" onclick="hidePopup()"></div>
    <div class="popup" id="popup">
        <h2>URL</h2>
        <h4>閲覧用</h4>
        <textarea id="viewUrl" readonly style="width:100%;"></textarea>
        <h4>削除用</h4>
        <textarea id="deleteUrl" readonly style="width:100%;"></textarea>
        <button onclick="hidePopup()">閉じる</button>
    </div>
    <script>
        const UPLOAD_URL = '<?= $base_url ?>/upload';
    </script>
    <script src="<?= $base_url ?>/js/uploader"></script>
</body>

</html>