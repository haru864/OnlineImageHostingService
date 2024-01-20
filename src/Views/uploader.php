<?

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
        document.getElementById("submit-btn").addEventListener("click", async function() {
            try {
                let formElement = document.querySelector("form");
                let formData = new FormData(formElement);
                const response = await fetch(<? echo $base_url ?>, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    body: formData
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                alert(data['url']);
            } catch (error) {
                console.error('Error:', error);
                alert(`Error: ${error}`);
            }
        });
    </script>
</body>

</html>