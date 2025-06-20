<?php
$host = "localhost";
$user = "u985354573_ayuatikahhh";
$pass = "ExploreJogja.atkh10"; 
$db   = "u985354573_explorejogja"; 

date_default_timezone_set('Asia/Jakarta');

$conn = new mysqli($host, $user, $pass, $db);

// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// } 

mysqli_query($conn, "SET time_zone = '+07:00'");
?>
