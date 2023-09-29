<?php
include '../systems/userhead.php';
include_once '../systems/db.php';

session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
}
?>
<link rel="stylesheet" type="text/css" href="css/home.css">

<link rel="stylesheet" type="text/css" href="css/proof.css">

<div class="card mt-2 text-center">
  <div class="card-header">
    <h3 class="mt-2">Silakan unggah bukti absen</h3>
  </div>
  <h4 class="mt-2">Pilih foto</h4>
  <div id="camera">
    <video id="video" autoplay></video>
  </div>
  <div class="webcam-camera mt-1 text-center text-center">
  <div class="btn-container">

    <button class= "btn btn-warning btn-lg rounded cambut mx-1" id="captureButton"><img src="../absenphotos/kamera.png" alt="Ambil Foto"></button>
    <label>Kamera</label>
  </div>
  <div class="btn-container">
    <label for="webcam" class="btn btn-success btn-lg btn-block rounded cambut mx-1">
      <img src="../absenphotos/galeri.png" alt="Unggah">
    </label>
    <label>Galeri</label>
    <input type="file" name="webcam" id="webcam" accept="image/jpg, image/png, image/gif, image/jpeg" capture="environment" onchange="displayFileName()">
  </div>
</div>
  <div id="previewContainer" class="mx-auto"></div> <!-- Added mx-auto class for centering -->
  <span id="upload-status" class="mb-1"></span>
</div>

<script>
  function displayFileName() {
    var fileInput = document.getElementById('webcam');
    var uploadStatus = document.getElementById('upload-status');
    var previewContainer = document.getElementById('previewContainer'); // Added preview container
    var absenButton = document.getElementById('absen-btn');

    if (fileInput.files && fileInput.files.length > 0) {
      var file = fileInput.files[0];
      var fileSize = file.size;
      var fileSizeStr = formatFileSize(fileSize);

      uploadStatus.textContent = 'Anda akan mengunggah ' + file.name + ' (' + fileSizeStr + ')';
      absenButton.disabled = false;

      var reader = new FileReader();
      reader.onload = function(e) {
        var base64Image = e.target.result;
        sessionStorage.setItem('uploadedImage', base64Image);

        previewContainer.style.display = 'block';
        var img = document.createElement('img');
        img.src = base64Image;
        previewContainer.innerHTML = '';
        previewContainer.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      uploadStatus.textContent = '';
      sessionStorage.removeItem('uploadedImage');
      absenButton.disabled = true;
      previewContainer.style.display = 'none';
      previewContainer.innerHTML = '';
    }
  }

  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<button class="btn my-2 rounded confirm btn-block btn-danger" id="absen-btn" data-action="absen" disabled>Kirim data absen di sini</button>
<p id="phpValues" class="text-white"></p>
<p id="demo"></p>
<script>
  var fileUploaded = false;
  var buttonClicked = false;

  document.getElementById('webcam').addEventListener('change', function() {
    fileUploaded = true;
  });

  document.getElementById('captureButton').addEventListener('click', function() {
    fileUploaded = true;
  });

  document.getElementById('absen-btn').addEventListener('click', function() {
    buttonClicked = true;
  });

  function checkUploadStatus(event) {
    if (!fileUploaded || !buttonClicked) {
  event.preventDefault();
  Swal.fire({
    icon: 'warning',
    title: 'Anda belum absen!',
    text: 'Silakan unggah foto lalu tekan tombol merah.',
    confirmButtonText: 'OK',
    showCancelButton: true,
    cancelButtonText: 'Keluar Tanpa Absen',
  }).then((result) => {
    if (result.isConfirmed) {
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      window.location.href = "home.php";
    }
  });
}
}

document.getElementById('homebotbar').addEventListener('click', checkUploadStatus);
document.getElementById('profilbotbar').addEventListener('click', checkUploadStatus);
document.getElementById('keluarbotbar').addEventListener('click', checkUploadStatus);

</script>

<script src="absensi.js"></script>

<script>
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                var videoElement = document.getElementById('video');
                videoElement.srcObject = stream;
                videoElement.play();
            })
            .catch(function (error) {
                console.log("Error accessing the webcam: " + error);
            });

        $('#captureButton').click(function () {
            var videoElement = document.getElementById('video');
            var canvas = document.createElement('canvas');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;

            var context = canvas.getContext('2d');
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

            var dataURL = canvas.toDataURL('image/png');

            var previewContainer = document.getElementById('previewContainer');
            var img = document.createElement('img');
            img.src = dataURL;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '100%';
            previewContainer.innerHTML = '';
            previewContainer.appendChild(img);

            sessionStorage.setItem('uploadedImage', dataURL);

            var absenButton = document.getElementById('absen-btn');
            absenButton.disabled = false;
        });
  document.getElementById('captureButton').addEventListener('click', function () {
  var videoElement = document.getElementById('video');
  var canvas = document.createElement('canvas');
  canvas.width = videoElement.videoWidth;
  canvas.height = videoElement.videoHeight;

  var context = canvas.getContext('2d');
  context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

  var dataURL = canvas.toDataURL('image/png');

  var previewContainer = document.getElementById('previewContainer');
  previewContainer.style.display = 'block';
  var img = document.createElement('img');
  img.src = dataURL;
  img.style.maxWidth = '100%';
  img.style.maxHeight = '100%';
  previewContainer.innerHTML = '';
  previewContainer.appendChild(img);
  previewContainer.style.display = 'block';

  sessionStorage.setItem('uploadedImage', dataURL);

  var absenButton = document.getElementById('absen-btn');
  absenButton.disabled = false;
});

</script>

<?php
  include_once '../systems/footer.php';
?>
