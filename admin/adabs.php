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

$sql = "SELECT absen.username, users.fullname, absen.longitude, absen.latitude, absen.date
  FROM absen
  INNER JOIN users ON absen.username = users.username
  WHERE users.type = 'worker' AND DATE(absen.date) = CURDATE()
  AND absen.date = (
      SELECT MAX(a2.date)
      FROM absen a2
      WHERE absen.username = a2.username AND DATE(a2.date) = CURDATE()
  )
  ORDER BY absen.date DESC";

$statement = $connection->prepare($sql);
$statement->execute();
$logs = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<link rel="stylesheet" type="text/css" href="admincss/adminstyle.css">

<div class="card mt-3">

  <div class="card-header text-center">
    <h2>Status Karyawan</h2>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <h4>Sudah Absen</h4>
      <table class="table table-striped">
        <thead class="table-secondary">
          <tr>
            <th>Nama</th>
            <th>Waktu / Lokasi</th>
            <th>Bukti</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $log):
            $sql = "SELECT COUNT(*) AS count FROM absen WHERE username = :username AND DATE(date) = CURDATE()";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':username', $log->username);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $absenCount = $result['count'];
            $isOdd = $absenCount % 2 !== 0;

            if ($isOdd) {
              $status = " (Absen)";
            } else {
              $status = " (Pulang)";
            }
            if ($absenCount == 0) {
              $status = " (Hari Ini Belum Absen)";
            }
            ?>
            <tr>
              <td>
                <?= $log->fullname . $status ?>
              </td>
              <td>
                <?php
                $lat = $log->latitude;
                $lng = $log->longitude;

                $geocode = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=APIKEY");
                $result = json_decode($geocode);

                if ($result->status == 'OK') {
                  $address = $result->results[0]->formatted_address;
                  echo "$log->date / ($lat, $lng): $address";
                } else {
                  echo "$log->date / ($lat, $lng): Not found";
                }
                ?>
              </td>
              <td>
                <a href="authy.php?user=<?= $log->username ?>" data-lightbox="image-<?= $log->username ?>">
                  <img src="authy.php?user=<?= $log->username ?>" alt="Belum Absen" class="img-thumbnail">
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
    $sqlUsers = "SELECT fullname FROM users WHERE type = 'worker' AND username NOT IN (
        SELECT absen.username
        FROM absen
        WHERE DATE(absen.date) = CURDATE()
        AND absen.date = (
            SELECT MAX(a2.date)
            FROM absen a2
            WHERE absen.username = a2.username AND DATE(a2.date) = CURDATE()
        )
      )";
    $statementUsers = $connection->prepare($sqlUsers);
    $statementUsers->execute();
    $usernames = $statementUsers->fetchAll(PDO::FETCH_COLUMN);

    ?>
    <div class="table-responsive">
      <h4>Belum Absen</h4>

      <table class="table table-striped">
        <thead class="table-secondary">
          <tr>
            <th>Nama Lengkap</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usernames as $username): ?>
            <tr>
              <td>
                <?= $username ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // Initialize Lightbox
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    });
  });
</script>

<?php
include_once '../systems/footer.php';
?>