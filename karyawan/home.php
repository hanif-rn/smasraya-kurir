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

if($acctype == "admin"){
  header("Location: ../admin/admin.php");
}

$sql = "SELECT tripid, latitude, longitude, date, type FROM logs WHERE username = :username";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$logs = $statement->fetchAll(PDO::FETCH_OBJ);

$sql = "SELECT fullname FROM users WHERE username = :username";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$fullname = $statement->fetchAll(PDO::FETCH_OBJ);
if (!empty($fullname)) {
  $firstRow = $fullname[0]; 
  $fullname = $firstRow->fullname; 
} else {
  $fullname = ""; 
}

$sql = "SELECT type FROM logs WHERE username = :username ORDER BY date DESC LIMIT 1";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
$initype = $result['type'];

$sql = "SELECT TIME(date) AS abstime FROM absen WHERE username = :username ORDER BY date DESC LIMIT 1";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$abstime = $statement->fetch(PDO::FETCH_ASSOC);

if (!empty($abstime)) {
  $abstime = $abstime['abstime']; 
} else {
  $abstime = "error"; 
}

$sql = "SELECT COUNT(*) AS count FROM absen WHERE username = :username AND DATE(date) = CURDATE()";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
$absenCount = $result['count'];
$isOdd = $absenCount % 2 !== 0;
?>
<link rel="stylesheet" type="text/css" href="css/home.css">

<div class="card mt-2 text-center">
  <h2 class="mt-2">Selamat Datang, <?= $fullname ?>!</h2>
</div>
<div class="card my-3 text-center">
  <div class="card-body mt-2 text-center">
      <div class="row d-flex text-center">
        <div class="col d-flex text-center">
          <div class="btn-container text-center">
            <?php if ($isOdd) : ?>
              <a class="btn flex-fill btn-lg my-2 rounded" href="absenphoto.php" id="absen-red">
              <img src="../absenphotos/absen.png" alt="Pulang"></a>
              <label for="pulang-btn" class="btn-label">Pulang</label>
            <?php else : ?>
              <a class="btn flex-fill btn-lg my-2 rounded" href="absenphoto.php" id="absen-red">
              <img src="../absenphotos/absen.png" alt="Absen"></a>
              <label for="pulang-btn" class="btn-label">Absen</label>
            <?php endif; ?>
          </div>
        </div>
        <div class="col d-flex">
          <div class="btn-container">
              <button class="btn btn-primary flex-fill btn-lg my-2 rounded" id="start-btn" data-action="start">
                <img src="../absenphotos/start.png" alt="Start"></button>
              <label for="start-btn" class="btn-label">Start</label>
          </div>
        </div>
      <?php if ($initype == "CHECK IN") : ?>
          <div class="col d-flex">
            <div class="btn-container">
              <button class="btn btn-primary flex-fill btn-lg my-2 rounded" id="checkout-btn" data-action="checkout">
                <img src="../absenphotos/checkio.png" alt="Check Out"></button>
              <label for="checkio-btn" class="btn-label">Check Out</label>
            </div>
          </div>
          <div class="col d-flex">
            <div class="btn-container">
              <button class="btn btn-primary flex-fill btn-lg my-2 rounded" id="finish-btn" data-action="finish">
                <img src="../absenphotos/finish.png" alt="Finish"></button>
              <label for="finish-btn" class="btn-label">Finish</label>
            </div>
          </div>
      <?php else : ?>
          <div class="col d-flex">
            <div class="btn-container">
              <button class="btn btn-primary flex-fill btn-lg my-2 rounded" id="checkin-btn" data-action="checkin">
                <img src="../absenphotos/checkio.png" alt="Check In"></button>
              <label for="checkio-btn" class="btn-label">Check In</label>
            </div>
          </div>
          <div class="col d-flex">
            <div class="btn-container">
              <button class="btn btn-primary flex-fill btn-lg my-2 rounded" id="finish-btn" data-action="finish">
                <img src="../absenphotos/finish.png" alt="Finish"></button>
              <label for="finish-btn" class="btn-label">Finish</label>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
</div>

<style>
  .scrollable-section {
  overflow-x: auto;
  white-space: nowrap;
}

.flex-row {
  display: flex;
}

#warning{
  width: 234px;
  height: 150px;
}
#history{
  width: 234px;
  height: 150px;
}
#flxrow{
  width: 543px;
}
#histbutt{
  width: 75;
  height: 100;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-size: 20px;
}
.scrl-crd{
  background-color: #53dee9;
  border: none;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3), 0 2px 4px rgba(0, 0, 0, 0.3);
  border-radius: 6px;
}

</style>

<div class="scrollable-section">
  <div id="flxrow" class="d-flex flex-row">
    <?php if ($absenCount > 0) : ?>
      <div id="warning" class="card text-bg-success my-1 mx-3 scrl-crd">
        <h4 class="card-header"">Status Absensi</h4>
        <div class="card-body">
          <p id="belum">Sudah absen (<?= $abstime?>)</p>
        </div>
      </div>
    <?php else : ?>
      <div id="warning" class="card text-bg-danger my-1 mx-3 scrl-crd">
        <h4 class="card-header">Status Absensi</h4>
        <div class="card-body">
          <p id="belum">Belum absen</p>
        </div>
      </div>
    <?php endif; ?>
    <div id="history" class="card text-bg-light my-1 mx-3 scrl-crd">
      <h4 class="card-header">History Pergerakan</h4>
      <div  class="card-body">
        <a id="histbutt" href="history.php"  class="btn btn-secondary border-dark">Masuk</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <p class="text-white" id="phpValues"></p>
<p id="demo"></p>
</div>

<script src="home.js"></script>

<?php
include_once '../systems/footer.php';
?>
