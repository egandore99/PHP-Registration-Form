<?php
  include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>PHP Register Page</title>
    <style>
        body{
            padding-left:10px;
            background-color:#1D2530;
            color:white;
        }
        .container{
            margin-top: 125px;
        }
        input{
            width: 100%;
        }
    </style>
</head>
<body>
 
   <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-md-offset-5 text-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                  method="POST">

                <input type="text" 
                       name="username" 
                       class="form-control" 
                       placeholder="Enter Username"><br>

                <input type="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Enter Password"><br>

                <input type="submit" 
                       value="Register" 
                       class="btn btn-success"><br>

                <a href="https://gandore.dev/" style="text-decoration:none;">
                <p style="font-size:14px;
                          color:white;
                          margin-top:16px;">&copy; Eduard Gandore</p></a>
            </form>
        </div>
    </div>
   </div>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);    

    if (empty($username)) {
        echo "Please enter a username.";
    } elseif (empty($password)) {
        echo "Please enter a password.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        if ($stmt) {
            $stmt->bind_param("ss", $username, $hash);

            try {
                $stmt->execute();
                echo "You are now registered!";
            } catch (mysqli_sql_exception $e) {
                if ($conn->errno == 1062) { 
                    echo "That username is taken. Please choose a different one.";
                } else {
                    echo "Error: " . $e->getMessage();
                }
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    mysqli_close($conn);
}
?>

