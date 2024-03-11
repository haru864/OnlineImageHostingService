<?php

use Settings\Settings;

$baseURL = Settings::env("BASE_URL");
?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>削除済みの画像</title>
</head>

<body>
    <div>
        <?= $delete_message ?>
    </div>
    <button type=“button” onclick="location.href='<?= $baseURL ?>'">ホーム</button>
</body>

</html>