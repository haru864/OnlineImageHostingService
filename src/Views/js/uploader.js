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
        const response = await fetch(UPLOAD_URL, {
            method: "POST",
            body: formData
        });
        if (!response.ok) {
            document.body.innerHTML = await response.text();
            return;
        }
        const data = await response.json();
        showPopup(data['viewUrl'], data['deleteUrl']);
    } catch (error) {
        console.error('Error:', error);
        alert(error);
    }
}
function showPopup(view_url, delete_url) {
    var data = {
        'view_url': view_url,
        'delete_url': delete_url
    };
    document.getElementById('viewUrl').textContent = data['view_url'];
    document.getElementById('deleteUrl').textContent = data['delete_url'];
    document.getElementById('popup').style.display = 'block';
    document.querySelector('.overlay').style.display = 'block';
}
function hidePopup() {
    document.getElementById('popup').style.display = 'none';
    document.querySelector('.overlay').style.display = 'none';
}
