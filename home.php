<?php

session_start();

if (!isset($_SESSION['signedin']) || $_SESSION['signedin'] !== true) {
    header("Location: signin.php");
    exit;
}

echo "Welcome, " . htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Aodach Clothing Store</h1>
</body>
</html>

