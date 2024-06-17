<?php
$mysqli = new mysqli("localhost", "root", "", "crop_disease_detection");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
