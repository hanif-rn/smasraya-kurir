<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Aplikasi Tracking Kurir</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@6.5.0/dist/ol.css" />
    <script src="https://cdn.jsdelivr.net/npm/ol@6.5.0/dist/ol.js"></script>
    <link rel="stylesheet" href="../systems/loading.css"> <!-- Include the loading CSS file -->
    <script src="../systems/loading.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  </head>
  <body>
  <style>
  .headpic{
    background-image: url('../absenphotos/atkbg.jpg'); 
    background-size: cover;
    background-position: center;
    background-color: lightblue;
  }
  .container {
  padding-bottom: 100px; 
  }

  .title-card {
    width: 90%; 
    height: auto; 
  }

  .homebotbar{
    padding: 20px;
  }
  .keluarbotbar{
    padding: 20px;
  }
  .profilbotbar{
    padding: 20px;
  }

  @media (min-width: 768px) {
    .title-card {
      width: 25%;
      margin-top: 1%;
      margin-bottom: 1%;
    }
  }
  </style>
  <div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner"></div>
  </div>
  <div class="container-fluid text-white text-center headpic">
     <img src="../absenphotos/atk.png" alt="Home" class="title-card rounded">
  </div>
  <nav class="navbar fixed-bottom navbar-dark text-center bg-primary">
    <div class="btn-container text-center mx-3">
      <a class="btn btn-primary flex-fill btn rounded btn-primary navbut" href="../karyawan/home.php" id="homebotbar">
      <img src="../absenphotos/home.png" alt="Home"></a>
      <label for="home-btn" class="btn-label text-light">Home</label>
    </div>
    <div class="btn-container text-center">
      <a class="btn btn-primary flex-fill btn rounded navbut" href="../navi/profile.php" id="profilbotbar">
      <img src="../absenphotos/profile.png" alt="Profil"></a>
      <label for="profil-btn" class="btn-label text-light">Profil</label>
    </div>
    <div class="btn-container text-center mx-3">
      <a class="btn btn-primary flex-fill btn rounded navbut" href="../navi/logout.php" id="keluarbotbar">
      <img src="../absenphotos/keluar.png" alt="Keluar"></a>
      <label for="keluar-btn" class="btn-label text-light">Keluar</label>
    </div>
  </nav>
  <div class="container">
