<?php

use Settings\Settings;

$base_url = Settings::env("BASE_URL");
?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>画像ページ</title>
    <style>
        .container {
            display: flex;
            align-items: center;
        }

        .container>div,
        .buttons>button {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div>
        Viewer: <?= $view_count ?>
    </div>
    <img src="<?= $base_url ?>/image/<?= $image_file_basename ?>" alt="">
    <div>
        <button type=“button” onclick="location.href='<?= $base_url ?>'">ホーム</button>
    </div>
</body>

</html>