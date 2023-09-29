<?php
    include_once '../systems/db.php';

    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
    }
    
    $username = $_SESSION['username'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    
    date_default_timezone_set('Asia/Jakarta');
    $time = date('Y-m-d H:i:s');
    $type = "CHECK IN";
    
    $sql = "SELECT trips FROM users WHERE username = :username";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    $tripid = $result['trips']; 
    
    $sql = "INSERT INTO logs(tripid, longitude, latitude, date, username, type) VALUES(:tripid, :longitude, :latitude, :date, :username, :type)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':tripid', $tripid, PDO::PARAM_INT);
    $statement->bindParam(':longitude', $longitude, PDO::PARAM_STR);
    $statement->bindParam(':latitude', $latitude, PDO::PARAM_STR);
    $statement->bindParam(':date', $time, PDO::PARAM_STR);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->bindParam(':type', $type, PDO::PARAM_STR);
    
    if ($statement->execute()) {
        $message = "Insertion success";
    }

    echo "Anda telah check in pada tanggal-waktu " . $time . " di koordinat " . $latitude . ", " . $longitude;
    exit;
?>
