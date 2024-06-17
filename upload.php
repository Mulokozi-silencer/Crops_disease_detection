<?php
require 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
        
        // Get the crop name from the form
        $crop_name = $_POST['cropName'];
        
        // Run Python script to process the image using DLib
        $output = shell_exec("python3 detect_disease.py " . escapeshellarg($target_file));
        
        // Debugging statement to check the output from the Python script
        echo "Output from Python script: " . $output;

        // Save the result in the database
        $sql = "INSERT INTO crops (user_id, crop_name, image_path, detection_result) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isss", $_SESSION['user_id'], $crop_name, $target_file, $output);
        
        if ($stmt->execute()) {
            echo "Disease detection result saved.";
            header("Location: dashboard.php");
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
