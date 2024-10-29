<?php

session_start();

if (!isset($_SESSION['signedin']) || $_SESSION['signedin'] !== true) {
    header("Location: signin.php");
    exit;
}


$host = "devweb2024.cis.strath.ac.uk";
$user = get_user();
$pass = get_password();
$dbname = $user;
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn -> connect_error) {
    die("connection failed: " . $conn -> connect_error);
}

$sql = "SELECT * FROM `Product`;";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}




$username = ($_SESSION['username']);



if (isset($_POST["productId"])) {
    $_SESSION['username'] = $username;
    $_SESSION['signedin'] = true;
    $_SESSION['productId'] = true;
    header("Location: home.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>


<div id="blankheader" style="background: url(images/highlands.jpg) no-repeat center center fixed; background-size: cover; ">
    <ul id="navbar">
        <li class="navcontent"><a class="active" href="home.php">Home</a></li>
        <li class="navcontent"><a href="mensclothes.php">Men</a></li>
        <li class="navcontent"><a href="home.php">Women</a></li>
        <li class="navcontent"><a href="home.php">Kids</a></li>
        <li class="navcontent"><a href="home.php">Bags</a></li>
        <li class="navcontent"><a href="home.php">Stores</a></li>
        <li class="navcontent" id="refbutton"><a href="home.php"><?php echo $username ?></a></li>
        <li class="navcontent" id="refbutton"><a href="basket.php">Basket</a></li>
    </ul>
    <div id="titlesection">
        <h2 id="theme"> THE FUTURE OF SCOTTISH CLOTHING </h2>
        <h1 id="subtheme"> AODACH CLOTHING STORE </h1>
        <h2 id="welcome"><?php echo $username?>, welcome to the Aodach Online Store. We hope you find exactly what you've been searching for.</h2>
        <div class="scroll-down-arrow">

            <span>&#x1F807;</span>
        </div>
    </div>
    <br>
</div>



    <div id="newProducts" style="background: url(images/storm.jpg) no-repeat center center fixed; background-size: cover; ">
        <h2>Our newest products: </h2>
        <?php
        if ($result->num_rows > 0) {


            while ($row = $result->fetch_assoc()) {
                $base64Image = base64_encode($row["image_data"]);

                ?>
            <form action="basket.php" method="post">
                <input type="hidden" name="productId" value="<?php echo $row['product_id']; ?>">
                <button type="submit" style="border: none; background: none; padding: 0;">
                    <img class="product_images" src="data:image/jpeg;base64,<?php echo $base64Image; ?>" alt="Product Image" style="width: 400px; height: auto;">
                </button>
            </form>
            <p>£<?php echo $row["price"]; ?></p>




            <?php

            }
            echo "</table>"; // Closing table tag
        } else {
            echo 'No current products :(';
        }
        ?>

    </div>



</body>
</html>

