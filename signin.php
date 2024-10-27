    <?php
    $username = '';
    ?>
    
    <?php
    

    
    
    
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
    
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    echo "<p>" . $result->num_rows . " rows found</p>";
    
    $error_messages = [];
    $valid = false;
    $error_count = 0;
    $password = '';
    if (isset($_POST["signIn"])) {
    
        echo 'SIGN IN CLICKED!';
    
        if (isset($_POST["username"]) && $_POST["username"] !== "") {
            $username = strip_tags($_POST["username"]);
            $valid = true;
        } else {
            $valid = false;
            $error_count++;
            $error_messages[$error_count] = 'Please enter a username.';
        }
    
        if (isset($_POST["password"]) && $_POST["password"] !== "") {
            $password = strip_tags($_POST["password"]);
            $valid = true;
        } else {
            $valid = false;
            $error_count++;
            $error_messages[$error_count] = 'Please enter a password.';
        }
    
        $accountPass = '';
        $match = false;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row["username"] === $username) {
                    echo "<br>" . 'account found';
                    $match = true;
                    $accountPass = $row["password"];
                    break;
                }
            }
        }
    
        if (!$match && $valid) {
            $error_count++;
            $error_messages[$error_count] = 'no account found called ' . $username;
        }
    
        if ($match) {
            if ( $accountPass = $row["password"] === $password) {
                echo "<br>" . 'PASSWORDS MATCHED SIGN IN COMPLETE';
                ?>
                <script>
                    window.location.href = "home.php";
                </script>
    
                <?php
                exit;
            } else {
                $error_count++;
                $error_messages[$error_count] = 'Incorrect password entered';
            }
        }
    
    
    } elseif (isset($_POST["forgot"])) {
        if ($_POST["username"] === null || $_POST["username"] == '') {
            $error_count++;
            $error_messages[$error_count] = 'Please enter a username to get your password reset';
        } else {
            $username = strip_tags($_POST["username"]);
            echo 'a reset link has been sent to the email linked to ' . $_POST["username"] ;
        }
    
    
    } elseif (isset($_POST["signUp"])) {
        echo 'SIGN UP CLICKED!';
        $NewUsername = '';
        if (isset($_POST["newUsername"]) && $_POST["newUsername"] !== "") {
            $NewUsername = strip_tags($_POST["newUsername"]);
            $valid = true;
        } else {
            $valid = false;
            $error_count++;
            $error_messages[$error_count] = 'Please enter a username.';
        }




        if (isset($_POST["newPassword"]) && $_POST["newPassword"] !== "") {
            $password = strip_tags($_POST["newPassword"]);
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
            $error_messages[$error_count] = 'Please enter a password.';
        }

        $passwordC = '';
        if (isset($_POST["confirmPassword"]) && $_POST["confirmPassword"] !== "") {
            $passwordC = strip_tags($_POST["confirmPassword"]);
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

        if (isset($_POST["newEmail"]) && ($_POST["newEmail"] !== "") && filter_var($_POST["newEmail"], FILTER_VALIDATE_EMAIL)) {
            $email = strip_tags($_POST["newEmail"]);
        } else {
            $error_count++;
            $error_messages[$error_count] = 'Please enter a valid email address.';
            $valid = false;
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row["account_name"] === $NewUsername) {
                    $match = true;
                    $error_count++;
                    $error_messages[$error_count] = 'An account already exists with that name, please enter another';
                    $valid = false;
                    break;
                }
            }
        }


        if ($valid && $email !== null) {

            mail($email, "Aodach Confirmation", "Dear ". $NewUsername . ",\nThank you for signing up to Aodach.\n" . "We hope you enjoy your time using our website.\n" . "Here's a link back to the sign in page: \nhttps://devweb2024.cis.strath.ac.uk/~xmb22143/Aodach%20Website/signin.php");
            echo "<br>" . 'A confirmation email has been sent to ' . $email;

            $date = date("Y:m:d");
            $time = date("H:i:s");
            $dateTime = $date . ' ' . $time;

            $sql = "INSERT INTO `xmb22143`.`account` (`account_name`, `email`, `password`) 
VALUES ('$NewUsername', '$email', '$password');";


            if ($conn->query($sql) === TRUE) {
                echo "<br> <br> " . "inserted new entry with id ".$conn->insert_id;
            } else {
                die ("Error: " . $sql); //. "<br>" . $conn->error); //FIXME debug only
            }



            echo 'account created successfully. Please sign in.';


            $conn->close();



        }
















    }
    
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($error_messages)) {
        echo "<br>";
        foreach ($error_messages as $error) {
            echo $error . "<br>";
        }
    }
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
    <h2>Welcome to the newest fashion outlet to hit the streets of Glasgow.</h2>
    <p> Aodach - pronounced OO-dakh - is the Scottish Gaelic word for clothes, highlighting our deep traditional links to the highlands of this country. </p>
    <form action="signin.php" method="post">
        <h2>Sign in:</h2>
        <p>Username:
            <input type="text" name="username" value = "<?php echo $username?>"/>
        </p>
        <p>Password:
            <input type="password" name="password"/>
        </p>
        <button type="submit" name="signIn" value="signIn">sign in</button>
        <button type="submit" name="forgot" value="forgot">forgot password</button>
    </form>
    <form action="signin.php" method="post">
        <h3>or</h3>
        <h2>Sign up:</h2>
        <p>Username:
            <input type="text" name="newUsername"/>
        </p>
        <p>Password:
            <input type="password" name="newPassword"/>
        </p>
        <p>Confirm Password:
            <input type="password" name="confirmPassword"/>
        </p>
        <p>Enter email:
            <input type="text" name="newEmail"/>
        </p>
    
        <button type="submit" name="signUp" value="signUp">sign up</button>
    
    
    </form>
    
    </body>
    </html>
    
