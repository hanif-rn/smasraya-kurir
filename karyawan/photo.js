const captureForm = document.getElementById('capture-form');

captureForm.addEventListener('submit', function(event) {
    event.preventDefault(); 
    capturePhoto();
});

function capturePhoto() {
    const photoDataUrl = canvas.toDataURL('image/jpeg');
    sendPhotoData(photoDataUrl);
}

function sendPhotoData(photoData) {
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'absen.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`photoData=${encodeURIComponent(photoData)}`);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Photo stored successfully!');
        } else {
            console.error('Error storing photo:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error('Error sending request to store photo.');
    };
}
