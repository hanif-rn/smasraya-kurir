var x = document.getElementById("demo");
var abspullabel = document.querySelector('label[for="pulang-btn"]');
if (document.querySelector('[data-action="absen"]')) {
  var absenButton = document.querySelector('[data-action="absen"]');
}
function getLocation(action) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      var imageElement = document.querySelector('#previewContainer img');
      var imageUrl = imageElement.src;

      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      var image = new Image();

      image.onload = function () {
        canvas.width = image.width;
        canvas.height = image.height;

        context.drawImage(image, 0, 0);

        canvas.toBlob(function (blob) {
          var compressedFile = new File([blob], 'compressed.jpg', { type: 'image/jpeg' }); // Adjust the file type and name as needed

          var formData = new FormData();
          formData.append('latitude', latitude);
          formData.append('longitude', longitude);
          formData.append('image', compressedFile);

          fetch('absen.php', {
            method: 'POST',
            body: formData,
          })
            .then(response => {
              if (response.ok) {
                return response.text();
              } else {
                x.innerHTML = "Failed to store coordinates.";
                throw new Error('Failed to store coordinates.');
              }
            })
            .then(responseText => {
              document.getElementById("phpValues").textContent = responseText;
            })
            .catch(error => {
              x.innerHTML = "Error occurred: " + error.message;
            });
        }, 'image/jpeg', 0.7); 

      };

      image.src = imageUrl;
    });
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}


const phpValuesElement = document.getElementById("phpValues");

const observer = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (mutation.type === "childList") {
      const responseText = phpValuesElement.textContent;
      if (responseText !== "Berhasil Absen") {
        Swal.fire({
          icon: 'warning',
          title: 'Error:',
          text: 'Absen gagal',
          confirmButtonText: 'OK',
        });
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil:',
          text: 'Anda sudah Absen',
          confirmButtonText: 'OK',
        }).then(() => {
          window.location.href = 'home.php'; // Redirect to home.php
        });
      }

    }
  });
});

observer.observe(phpValuesElement, { childList: true });


function compressImage(file, callback) {
  var maxSizeInBytes = 750 * 1024; 

  if (file.size <= maxSizeInBytes) {
    callback(file);
    return;
  }

  var image = new Image();
  var canvas = document.createElement('canvas');
  var context = canvas.getContext('2d');

  var reader = new FileReader();
  reader.onload = function(e) {
    image.onload = function() {
      var aspectRatio = image.width / image.height;
      var maxWidth = Math.sqrt(maxSizeInBytes * aspectRatio);
      var maxHeight = maxWidth / aspectRatio;

      canvas.width = maxWidth;
      canvas.height = maxHeight;

      context.drawImage(image, 0, 0, maxWidth, maxHeight);

      var compressedData = canvas.toDataURL('image/jpeg', 0.7); // Adjust the JPEG quality as needed
      var compressedBlob = dataURItoBlob(compressedData);
      var compressedFile = new File([compressedBlob], file.name, { type: 'image/jpeg' }); // Adjust the file type as needed
      callback(compressedFile);
    };

    image.src = e.target.result;
  };

  reader.readAsDataURL(file);
}

function dataURItoBlob(dataURI) {
  var byteString = atob(dataURI.split(',')[1]);
  var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
  var ab = new ArrayBuffer(byteString.length);
  var ia = new Uint8Array(ab);
  for (var i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }
  return new Blob([ab], { type: mimeString });
}

var buttons = document.querySelectorAll('[data-action]');
buttons.forEach(function (button) {
  button.addEventListener('click', function () {
    var action = button.dataset.action;
    getLocation(action);
  });
});
