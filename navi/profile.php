<?php
include '../systems/userhead.php';
include_once '../systems/db.php';

session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
}

$username = $_SESSION['username'];

$sql = "SELECT fullname, trips, username, email, phone, type FROM users WHERE username = :username";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ($result) {
  $fullname = $result['fullname'];
  $trips = $result['trips'];
  $username = $result['username'];
  $type = $result['type'];
  if($type == 'admin'){
    $type = 'Admin';
  } else {
    $type = 'Karyawan';
  }
  $email = $result['email'];
  $phone = $result['phone'];
} else {
  $fullname = '';
  $trips = '';
  $username = '';
  $type = '';
  $email = '';
  $phone = '';
}
?>
<link rel="stylesheet" type="text/css" href="profile.css">

<div class="card mt-2 text-center">
  <h2 class="mt-1">Profil <?= $fullname ?></h2>
</div>
<div class="card mt-2 bg-secondary text-center">
  
  <img src="../absenphotos/profile.png" class="my-1" id="pfp"></h2>
</div>
<div class="card mt-2">
    <div class="card-header">
        <h3>Biodata</h3>
    </div>  
    <div class="card-body">
        <h4>Nama Lengkap (Posisi):</h4>
        <p><?=$fullname?> (<?=$type?>)</p>
        <hr>
        <h4>Username:</h4>
        <p><?=$username?></p>
        <hr>
        <h4>E-Mail:</h4>
        <p><?=$email?></p>
        <hr>
        <h4>No. Telpon:</h4>
        <p><?=$phone?></p>
        <hr>
        <h4>Jumlah Perjalanan:</h4>
        <p><?=$trips?></p>
        <hr>
    </div>
</div>
<?php
include_once '../systems/footer.php';
?>
