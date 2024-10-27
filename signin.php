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
    
    $sql = "SELECT * FROM `accounts_info`;";
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
        echo 'SIGN UP TO BE IMPLEMENTED';
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
            <input type="text" name="username"/>
        </p>
        <p>Password:
            <input type="password" name="password"/>
        </p>
        <p>Confirm Password:
            <input type="password" name="password"/>
        </p>
        <p>Enter email:
            <input type="text" name="email"/>
        </p>
    
        <button type="submit" name="signUp" value="signUp">sign up</button>
    
    
    </form>
    
    </body>
    </html>
    
