<?php
include '../systems/userhead.php';
include_once '../systems/db.php';

session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
}
$username = $_SESSION['username'];

$sql = "SELECT type FROM users WHERE username = :username LIMIT 1";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
$acctype = $result['type'];

if ($acctype != "admin") {
  header("Location: ../karyawan/home.php");
}

if (isset($_GET['tripid']) && isset($_GET['user'])) {
  $tripid = $_GET['tripid'];
  $user = $_GET['user'];

  $sql = "SELECT latitude, longitude, date FROM logs WHERE username = :username AND tripid = :tripid AND type != 'CHECK OUT' ORDER BY date ASC";
  $statement = $connection->prepare($sql);
  $statement->bindValue(':username', $user);
  $statement->bindValue(':tripid', $tripid);
  $statement->execute();
  $logs = $statement->fetchAll(PDO::FETCH_OBJ);
} else {
  header("Location: admin.php");
  exit();
}
?>
<link rel="stylesheet" type="text/css" href="admincss/adminstyle.css">

<div class="card mt-3">
  <div class="card-header text-center">
    <h1>Map for Trip ID:
      <?= $tripid ?>
    </h1>
  </div>
  <div class="card-body">
    <div id="map" style="height: 400px;"></div>
  </div>
</div>
<a href="adhist.php?user=<?= $user ?>" class="btn btn-light btn-lg mt-4 border-dark">Kembali</a>



<script>
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      center: { lat: <?= $logs[0]->latitude ?>, lng: <?= $logs[0]->longitude ?> }
    });

    var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer({
      map: map,
      suppressMarkers: true
    });

    var waypoints = [
      <?php foreach ($logs as $log): ?>
              { location: { lat: <?= $log->latitude ?>, lng: <?= $log->longitude ?> } },
      <?php endforeach; ?>
    ];

    var request = {
      origin: waypoints[0].location,
      destination: waypoints[waypoints.length - 1].location,
      waypoints: waypoints.slice(1, waypoints.length - 1),
      travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function (response, status) {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }
    });

    var bounds = new google.maps.LatLngBounds();
    <?php foreach ($logs as $index => $log): ?>
      var marker = new google.maps.Marker({
        position: { lat: <?= $log->latitude ?>, lng: <?= $log->longitude ?> },
        map: map,
        title: 'Date: <?= $log->date ?>',
        label: String(<?php echo $index + 1; ?>)
      });
      bounds.extend(marker.getPosition());
    <?php endforeach; ?>

    map.fitBounds(bounds);
  }
  initMap();
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=APIKEY" async defer></script>

<?php
include_once '../systems/footer.php';
?>