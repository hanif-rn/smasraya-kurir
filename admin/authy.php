<?php
  include_once '../systems/db.php';

  if (isset($_GET['user'])) {
      $username = $_GET['user'];
      $sql = "SELECT * FROM absen WHERE username = :username ORDER BY date DESC LIMIT 1";
      $statement = $connection->prepare($sql);
      $statement->bindValue(':username', $username);
      $statement->execute();
      $entry = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit();
    }

  if ($entry) {
      $imageData = $entry['proof'];
      $mimeType = $entry['mime_type'];
      $decodedImageData = base64_decode($imageData);
      header("Content-type: $mimeType");
      echo $decodedImageData;
  } else {
      echo 'No entry found in the absen table.';
  }
?>