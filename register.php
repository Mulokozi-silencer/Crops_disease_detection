<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}
?>
<html>
<head><title>Register</title>
<link rel="stylesheet" href="styles.css"></head>
<body>
    <h2 align="center">Crop Disease Detection</h2>
    <form method="post" action="register.php">
        <h2 align="center">Register Form</h2>
        <div class="field input">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="field input">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="field">
            <input type="submit" class="btn" name="submit" value="Register" required>
        </div>
        <div class="links">
                    Already a member? <a href="login.php">Sign In</a>
        </div>
    </form>
</body>
</html>
