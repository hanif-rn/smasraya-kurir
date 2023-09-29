<?php
  include '../systems/userhead.php';
  include_once '../systems/db.php';

    if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $fullname = $_POST['fullname'];    
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $trips = 0;
    $type = 'worker';
    
    $sql = "INSERT INTO users(trips, username, password, fullname, type, email, phone) VALUES(:trips, :username, :password, :fullname, :type, :email, :phone)";

    $statement = $connection->prepare($sql);
    if ( $statement->execute( [
        ':trips' => $trips,
        ':username' => $username,
        ':password' => $password,
        ':fullname' => $fullname,
        ':type' => $type,
        ':email' => $email,
        ':phone' => $phone
        ])){
            $message = "Insertion success";
            header("Location: admin.php");
        } 
}
?>

<link rel="stylesheet" type="text/css" href="admincss/regi.css">

<section class="vh-100" style="background-color: #fff;">
  <div class="container my-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong bg-light" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h2 class="mb-4">Buat Akun Baru</h2>
            <form method="post" action="register.php">

            <div class="form-outline mb-4">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" class="form-control" id="fullname" name="fullname">
            </div>

            <div class="form-outline mb-4">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="form-outline mb-4">
                <label for="email">E-Mail</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>

            <div class="form-outline mb-4">
                <label for="phone">No. HP</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>

            <div class="form-outline mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" name="submit" value="submit" class="btn btn-dark btn-block mb-4">Buat Akun</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
    include_once '../systems/footer.php';
?>