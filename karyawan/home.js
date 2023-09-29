var x = document.getElementById("demo");
  var checkinButton;
  var abspullabel = document.querySelector('label[for="pulang-btn"]');
  var ciolabel = document.querySelector('label[for="checkio-btn"]');
  var belumElement = document.getElementById('belum');
  if (document.querySelector('[data-action="checkin"]')) {
    checkinButton = document.querySelector('[data-action="checkin"]');
  } else {
    checkinButton = document.querySelector('[data-action="checkout"]');
  }
  var warningElement = document.getElementById('warning');
  var startButton = document.querySelector('[data-action="start"]');
  var finishButton = document.querySelector('[data-action="finish"]');
  if (document.querySelector('[data-action="absen"]')) {
    absenButton = document.querySelector('[data-action="absen"]');
  } else {
    absenButton = document.querySelector('[data-action="pulang"]');
  }
  var isCheckIn = true;
  var isStarted = true;

  function getLocation(action) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        fetch(action + '.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'latitude=' + latitude + '&longitude=' + longitude,
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
      });
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function toggleCheckInCheckout() {
    if (checkinButton.dataset.action == 'checkin') {
      ciolabel.textContent = 'Check Out';
      checkinButton.dataset.action = 'checkout';
    } else {
      ciolabel.textContent = 'Check In';
      checkinButton.dataset.action = 'checkin';
    }
    isCheckIn = !isCheckIn;
  }

  function toggleStartFinish() {
    if (isStarted) {
      finishButton.disabled = false;
      startButton.disabled = true;
    } else {
      finishButton.disabled = true;
      startButton.disabled = false;
    }
    isStarted = !isStarted;
  }

const phpValuesElement = document.getElementById("phpValues");

const observer = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (mutation.type === "childList") {
      const responseText = phpValuesElement.textContent;
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: responseText,
        confirmButtonText: 'OK',
      });
    }
  });
});


const observerConfig = { childList: true };
observer.observe(phpValuesElement, observerConfig);


  var buttons = document.querySelectorAll('[data-action]');
  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      var action = button.dataset.action;
      getLocation(action);
      if (action === 'checkin' || action === 'checkout') {
        toggleCheckInCheckout();
      } else if (action === 'start') {
        finishButton.disabled = false;
        startButton.disabled = true;
        checkinButton.disabled = false;
      } else if (action === 'finish') {
        finishButton.disabled = true;
        startButton.disabled = false;
        checkinButton.disabled = true;
      } else if (action === 'absen') {
        absenButton.dataset.action = 'pulang';
        abspullabel.textContent = 'Pulang';
        belumElement.textContent = 'Sudah absen';
        warningElement.classList.add('text-bg-success');
        warningElement.classList.remove('text-bg-light');
        warningElement.classList.remove('text-bg-danger');
      } else if (action === 'pulang') {
        belumElement.textContent = 'Sudah pulang';
        abspullabel.textContent = 'Absen';
        absenButton.dataset.action = 'absen';
        warningElement.classList.add('text-bg-secondary');
        warningElement.classList.remove('text-bg-success');
      }
    });
  });