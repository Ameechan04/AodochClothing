<?php
$email = '';
$password = '';
$passwordC = '';
$error_count = 0;
$error_messages = [];

$host = "devweb2024.cis.strath.ac.uk";
$user = get_user();
$pass = get_password();
$dbname = $user;
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn -> connect_error) {
    die("connection failed: " . $conn -> connect_error);
}

$sql = "SELECT * FROM `account`;";
$result = $conn->query($sql);
$valid = false;
if (!$result) {
    die("Query failed: " . $conn->error);
}


if (isset($_POST["email"]) && $_POST["email"] !== "") {
    $email = strip_tags($_POST["email"]);
    $valid = true;
} else {
    $valid = false;
    $error_count++;
    $error_messages[$error_count] = 'Please enter an email.';
}

if (isset($_POST["password"]) && $_POST["password"] !== "") {
    $password = strip_tags($_POST["password"]);
    if (strlen($password) >= 8) {
        $valid = true;
    } else {
        $valid = false;
        $error_count++;
        $error_messages[$error_count] = 'Password must be longer than 7 characters';
    }
} else {
    $valid = false;
    $error_count++;
    $error_messages[$error_count] = 'Please enter a new password.';
}


if (isset($_POST["passwordC"]) && $_POST["passwordC"] !== "") {
    $passwordC = strip_tags($_POST["passwordC"]);
    $valid = true;
} else {
    $valid = false;
    $error_count++;
    $error_messages[$error_count] = 'Please confirm your password';
}
if ($valid) {
    if ($password === $passwordC) {
        $valid = true;
    } else {
        $valid = false;
        $error_count++;
        $error_messages[$error_count] = 'Passwords do not match, please re-enter';
    }
}


$match = false;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["email"] === $email) {
            echo "<br>" . 'account found';
            $match = true;
            break;
        }
    }
}
if ($match) {
    $sql = "UPDATE `account` SET `password` = '" . $password . "' WHERE `account`.`account_name` = '" . $row["account_name"] . "';";

    if ($conn->query($sql) === TRUE) {
        // echo "<br> <br> " . "inserted new entry with id ".$conn->insert_id;
    } else {
        die ("Error: " . $sql); //. "<br>" . $conn->error); //FIXME debug only
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Aodach Clothing Store Password Reset</h1>
<form action="passwordReset.php" method="post">
    <p>Enter your email address:
        <input type="text" name="email" value = "<?php echo $email?>"/>
    </p>
    <p>Enter your new password:
        <input type="password" name="password"/>
    </p>
    <p>Confirm your new password:
        <input type="password" name="passwordC"/>
    </p>
    <button type="submit" name="submit" value="submit">Submit</button>
</form>
</body>
</html>


<?php
$conn->close();
?>
