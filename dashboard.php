<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT crop_name, image_path, detection_result FROM crops WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<html>
<head><title>Dashboard</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1 align="center">Welcome to Crop Disease Detection System</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        Crop name:
        <input type="text" name="cropName" id="cropName">
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <h2>Your Crop Images and Detection Results</h2>
    <table>
        <tr>
            <th>Crop Name</th>
            <th>Image</th>
            <th>Detection Result</th>
        </tr>
        <?php 
        // Debugging statement to check if there are rows
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Crop Image" style="width:100px;"></td>
                    <td><?php echo htmlspecialchars($row['detection_result']); ?></td>
                </tr>
            <?php endwhile; 
        else: ?>
            <tr>
                <td colspan="3">No records found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
