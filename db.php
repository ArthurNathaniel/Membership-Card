<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "membership_card";

$servername = "suamemagazinesparepartsdealersassociation.com";
$username = "u500921674_membership";
$password = "OnGod@123";
$dbname = "u500921674_membership";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
