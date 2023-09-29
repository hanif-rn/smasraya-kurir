<?php
  include '../systems/header.php';
  include_once '../systems/db.php';


    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        
        $sql = "SELECT * FROM users WHERE username=:username AND password=:password";
        
        $statement = $connection->prepare($sql);
        if ( $statement->execute( [
            ':username' => $username,
            ':password' => $password
            ])){
                if( $statement->rowCount() >= 1){
                    session_start();
                    $_SESSION['username'] = $username;
                    
                    header("Location: ../karyawan/home.php");
                }
                
            } 
    }
?>
<section class="vh-100" style="background-color: #fff;">
  <div class="container my-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong bg-light" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h2 class="mb-4">Log in User</h2>
            <form method="post" action="login.php">

            <div class="form-outline mb-4">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="form-outline mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" name="submit" value="submit" class="btn btn-dark btn-block mb-4">Submit</button>
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