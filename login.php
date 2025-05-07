<?php

session_start();

if(isset($_SESSION["email"])){
    header("location: ./menu.php");
    exit;
}


$email = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['em-auth']);
    $password = trim($_POST['pass-auth']);

    if(empty($email) || empty($password)){
        $error = "Email and/or Password is required.";
    } else{
        include "tools/db.php";
        $dbConnection = getDBConnection();
     
        $statement = $dbConnection->prepare(
            "SELECT id, first_name, last_name, password, createdAt FROM users WHERE email = ?"
        );

        $statement->bind_param('s',$email);
        $statement->execute();


        $statement->bind_result($id, $first_name, $last_name, $stored_password, $createdAt);




        

        if($statement->fetch()){

            if(password_verify($password,$stored_password)){
                $_SESSION["id"] = $id;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["email"] = $email;
                $_SESSION["createdAt"] = $createdAt;
                
                header("location: ./menu.php");
                exit;
            }
        }

        $statement->close();

        $error = "Email or Password Invalid";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page | Crust Corner</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="Ham and Cheese Pizza.jpg">
</head>
<body>

    <header>
        <div class="logo">Welcome to Crust Corner!</div>
    </header>

    <div class="login-container">
        <h1>Login to Crust Corner</h1>
        <form method="post "id="loginForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" id="loginBtn">Login</button>
            <a href="./index.php">
            <button type="button" id="createAccountButton" class="create-account-button">Create New Account</button>
                      </a>
        </form>
    </div>

    <footer>
        Crust Corner where every slice feels like home!
</footer>

</body>
</html>

