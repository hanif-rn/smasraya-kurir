<?php
include '../systems/userhead.php';
include_once '../systems/db.php';


session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
}

$username = $_SESSION['username'];


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

$sql = "SELECT type FROM users WHERE username = :username LIMIT 1";
$statement = $connection->prepare($sql);
$statement->bindValue(':username', $username);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
$acctype = $result['type'];

if($acctype != "admin"){
  header("Location: ../karyawan/home.php");
}
$sql = "SELECT * FROM users WHERE type = 'worker' ORDER BY fullname ASC";
$statement = $connection->prepare($sql);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_OBJ);
?>

<link rel="stylesheet" type="text/css" href="admincss/adminstyle.css">

<div class="card mt-2 text-center">
  <h2 class="mt-2">Selamat Datang, <?= $fullname ?>!</h2>
</div>
<div class="card my-3 text-center">
  <div class="card-body mt-2 text-center">
      <div class="row d-flex text-center">
        <div class="col d-flex">
          <div class="btn-container">
              <a class="btn btn-primary flex-fill btn-lg my-2 rounded" id="start-btn" data-action="start" href="register.php">
                <img src="../absenphotos/regi.png" alt="Start"></a>
              <label for="start-btn" class="btn-label">Register</label>
          </div>
        </div>
        <div class="col d-flex">
          <div class="btn-container">
              <a class="btn btn-primary flex-fill btn-lg my-2 rounded" id="finish-btn" data-action="start" href="adabs.php">
                <img src="../absenphotos/absen.png" alt="Lacak Absensi"></a>
              <label for="start-btn" class="btn-label">Absensi</label>
          </div>
        </div>
      </div>
    </div>
</div>

<hr>
<h4>Karyawan</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row) : ?>
            <tr>
                <td><?php echo $row->fullname; ?></td>
                <td>
                    <a href="adhist.php?user=<?php echo $row->username; ?>" class="btn btn-secondary refbutt">Sejarah</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include_once '../systems/footer.php';
?>

