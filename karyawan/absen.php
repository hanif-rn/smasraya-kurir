<?php
include_once '../systems/db.php';

session_start();
if (!isset($_SESSION['username'])){
    header("Location: ../index.php");
}

$username = $_SESSION['username'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
date_default_timezone_set('Asia/Jakarta');
$time = date('Y-m-d H:i:s');

if(isset($_FILES['image'])) {
    $image = $_FILES['image'];
    if ($image['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($image['tmp_name']);
        $base64Image = base64_encode($imageData);

        $sql = "INSERT INTO absen (username, longitude, latitude, date, proof) VALUES (:username, :longitude, :latitude, :date, :proof)";
        $statement = $connection->prepare($sql);
        if ($statement->execute([
            ':username' => $username,
            ':longitude' => $longitude,
            ':latitude' => $latitude,
            ':date' => $time,
            ':proof' => $base64Image
        ])) {
            $message = "Berhasil Absen";
        } else {
            $message = "Tidak bisa Absen, silakan coba lagi";
        }
    } else {
        $username = $_SESSION['username'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    date_default_timezone_set('Asia/Jakarta');
    $time = date('Y-m-d H:i:s');

    $sql = "INSERT INTO absen(username, longitude, latitude, date) VALUES(:username, :longitude, :latitude, :date)";
    $statement = $connection->prepare($sql);
    if ( $statement->execute( [
        ':username' => $username,
        ':longitude' => $longitude,
        ':latitude' => $latitude,
        ':date' => $time
    ])){
        $message = "Berhasil Absen";
    }
    }
} else {
    $message = "No image file uploaded";
}

echo $message;
exit;
?>
