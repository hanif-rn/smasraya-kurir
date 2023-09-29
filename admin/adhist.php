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

if (isset($_GET['user'])) {
  $user = $_GET['user'];
} else {
  header("Location: admin.php");
  exit();
}

$sql = "SELECT fullname FROM users WHERE username = :username";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $user);
$statement->execute();
$fullname = $statement->fetchAll(PDO::FETCH_OBJ);
if (!empty($fullname)) {
  $firstRow = $fullname[0]; // Get the first element
  $fullname = $firstRow->fullname; // Assign the value to the $fullname variable
} else {
  $fullname = ""; // Handle the case where the array is empty
}
?>


<?php
$sql = "SELECT tripid, latitude, longitude, date, type FROM logs WHERE username = :username";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $user);
$statement->execute();
$logs = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<?php
$tripids = array();

foreach ($logs as $log) {
  $tripid = $log->tripid;
  if (!in_array($tripid, $tripids)) {
    $tripids[] = $tripid;
  }
}
?>
<link rel="stylesheet" type="text/css" href="admincss/adminstyle.css">

<div class="card mt-3">
  <div class="card-header text-center">
    <h1>Rangkuman Pergerakan
      <?= $fullname ?>
    </h1>
  </div>

  <div class="card-body">
    <?php foreach ($tripids as $tripid): ?>
      <div class="table-responsive mb-4">
        <h3 class="text-center">Trip ID:
          <?= $tripid ?>
        </h3>
        <table class="table table-striped">
          <thead class="table-secondary">
            <tr>
              <th>Status</th>
              <th>Latitude / Longitude / Address</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $log): ?>
              <?php if ($log->tripid == $tripid): ?>
                <tr>
                  <td>
                    <?= $log->type ?>
                  </td>
                  <td>
                    <?php
                    $lat = $log->latitude;
                    $lng = $log->longitude;

                    $geocode = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=");
                    $result = json_decode($geocode);

                    if ($result->status == 'OK') {
                      $address = $result->results[0]->formatted_address;
                      echo "($lat, $lng): $address";
                    } else {
                      echo "($lat, $lng): Not found";
                    }
                    ?>
                  </td>
                  <td>
                    <?= $log->date ?>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="text-center">
          <a href="admap.php?tripid=<?= $tripid ?>&user=<?= $user ?>" class="btn btn-light border-dark">Tampilkan Peta</a>
        </div>
      </div>
      <hr>
    <?php endforeach; ?>
  </div>
</div>

<?php
include_once '../systems/footer.php';
?>