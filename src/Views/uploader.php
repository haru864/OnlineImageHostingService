<?php

use Settings\Settings;

$base_url = Settings::env("BASE_URL");
?>

<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Online Image Hosting Service</title>
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
    <form id="uploadForm">
        <input type="file" id="fileUpload" name="fileUpload">
        <input type="button" value="アップロード" onclick="uploadFile()">
    </form>
    <script>
        function validateFile() {
            const validFiles = ['jpg', 'jpeg', 'png', 'gif'];
            const fileInput = document.getElementById('fileUpload');
            const file = fileInput.files[0];
            const fileName = file.name;
            let extension = fileName.split('.').pop().toLowerCase();
            if (!validFiles.includes(extension)) {
                throw new Error('Invalid file\njpg,jpeg,png,gif are allowed');
            }
        }
        async function uploadFile() {
            try {
                validateFile();
                let formElement = document.querySelector("form");
                let formData = new FormData(formElement);
                const response = await fetch('<?= $base_url ?>/register', {
                    method: "POST",
                    body: formData
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                // TODO alertだとコピペできない場合がある
                alert("閲覧用URL\n" + data['view_url']);
                alert("削除用URL\n" + data['delete_url']);
            } catch (error) {
                console.error('Error:', error);
                alert(error);
            }
        };
    </script>
</body>

</html>