<?php

session_start();

if (!isset($_SESSION['signedin']) || $_SESSION['signedin'] !== true) {
    header("Location: signin.php");
    exit;
}



echo "<h1>ADMIN LOGGED IN </h1>";
echo "Welcome, " . htmlspecialchars($_SESSION['username']);


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


if (isset($_POST["addProduct"])) {
    $error_count = 0;
    $error_messages = [];
    $productName = '';
    if (isset($_POST["productName"]) && $_POST["productName"] !== "") {
        $productName = strip_tags($_POST["productName"]);
        $valid = true;
    } else {
        $valid = false;
        $error_count++;
        $error_messages[$error_count] = 'Please enter a product name.';
    }

    if (isset($_POST["price"]) && $_POST["price"] !== "") {
        $price = strip_tags($_POST["price"]);

        if (is_numeric($price)) {
            if ($price > 0) {
                $valid = true;
            } else {
            $valid = false;
            $error_count++;
            $error_messages[$error_count] = 'Please enter a positive price.';
            }
        } else {
            $valid = false;
            $error_count++;
            $error_messages[$error_count] = 'Please enter a numeric value.';
        }

    } else {
        $valid = false;
        $error_count++;
        $error_messages[$error_count] = 'Please enter a price.';
    }





    if ($valid) {
        if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["product_name"] === $productName) {
                        $valid = false;
                        $error_count++;
                        $error_messages[$error_count] = 'Name already taken';
                        break;
                    }
                }
        }
    }

    if ($valid) {
        $sql = "INSERT INTO `xmb22143`.`Product` (`price`, `product_name`) 
VALUES ('$price', '$productName');";


        if ($conn->query($sql) === TRUE) {
            echo "<br> <br> " . "inserted new entry with id ".$conn->insert_id;
        } else {
            die ("Error: " . $sql); //. "<br>" . $conn->error); //FIXME debug only
        }



        echo 'product created successfully.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    }
    $result->data_seek(0);



} elseif (isset($_POST["removeProduct"])) {
    if (isset($_POST["productR"]) && $_POST["productR"] !== "") {
        $productName = strip_tags($_POST["productR"]);
        $valid = true;
    } else {
        $valid = false;
        echo 'enter a product name';
    }
    $valid = false;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["product_name"] === $productName) {
                $valid = true;
                break;
            }
        }
    }

    if ($valid) {
        $sql = "DELETE FROM Product WHERE product_name = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error)); }

        $stmt->bind_param("s", $productName);

        if ($stmt->execute()) {
            echo 'Deleted product';
        } else {
            die("Error executing query");
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo 'name could not be matched to an existing product!';
    }

}
$result->data_seek(0);

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
    <title>Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Aodach Admin Control Page</h1>

<form action="admin.php" method="post" enctype="multipart/form-data">
<h2>Add Product:</h2>
<p>product name:
    <input type="text" name="productName"/>
</p>
<p>price:
    <input type="number" name="price" step = "any"/>
</p>
<p>Upload an Image</p>
    <label for="file">Choose an image:</label>
    <input type="file" name="file" id="file" accept="image/*">
    <br><br>


    <button type="submit" name="addProduct" value="addProduct">add product</button>


</form>

<h1> All current products: </h1>
<?php
if ($result->num_rows > 0) {
echo "<table>
    <tr>
        <th>Product ID</th>
        <th>Price</th>
        <th>Product Name</th>
    </tr>";

    while ($row = $result->fetch_assoc()) {
    echo "<tr>" .
    "<td>" . $row["product_id"] . "</td>" .
    "<td>" . $row["price"] . "</td>" .
    "<td>" . $row["product_name"] . "</td>" .
    "</tr>";
    "</table>";
}
} else {
    echo 'No current products :(';
}




?>

<h2>Remove a product: </h2>
<form action="admin.php" method="post">
    <p>product name:
        <input type="text" name="productR"/>
    </p>
    <button type="submit" name="removeProduct" value="removeProduct">remove</button>
</form>



</body>
</html>

<?php
$conn->close();
?>

