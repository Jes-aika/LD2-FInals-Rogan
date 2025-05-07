<?php

session_start();

$authenticated = false;
if(isset($_SESSION["email"])){
    $authenticated = true;
}

$first_name = "";
$last_name = "";
$email = "";
$phone = "";
$address = "";

$fname_err = "";
$Lname_err = "";
$email_err = "";
$pass_err = "";
$Cpass_err = "";


$error = false;

IF($_SERVER['REQUEST_METHOD'] == 'POST'){
    $first_name = $_POST['fname'];
    $last_name = $_POST['Lname'];
    $email = $_POST['em'];
    $password = $_POST['pass'];
    $confirmed_pass = $_POST['Cpass'];




    if(empty($first_name)){
        $fname_err = "First Name is required.";
        $error = true;
    }
    if(empty($last_name)){
        $Lname_err = "Last Name is required.";
        $error = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Email format invalid.";
        $error = true;
    }
    

    include "tools/db.php";
    $dbConnection = getDBConnection();

    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);

    $statement->execute();


    $statement->store_result();
    if ($statement->num_rows > 0){
        $email_err = "Email already used.";
        $error = true;
    }

    $statement->close();

    if(strlen($password) < 6){
        $pass_err = "Password must be greater than 7 characters.";
        $error = true;
    }
    if($confirmed_pass != $password){
        $Cpass_err = "Passwords do not match.";
        $error = true;
    }


    if(!$error){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $statement = $dbConnection->prepare(
            "INSERT INTO users (first_name, last_name, email, password, createdAt) ".
            "VALUES (?,?,?,?,?)"
        );

    $statement->bind_param('sssss', $first_name,$last_name,$email,$password,$created_at);

    $statement->execute();

    $insert_id = $statement->insert_id;
    $statement->close();



    $_SESSION["id"] = $insert_id;
    $_SESSION["first_name"] = $first_name;
    $_SESSION["last_name"] = $last_name;
    $_SESSION["email"] = $email;
    $_SESSION["created_at"] = $created_at;

    header("Location: home.php");
    exit();
    }
}
if($authenticated){
    header("Location: login.php");
    exit();
} else {
?>

<html>
<head>
  <title>User Registration</title>
  <link rel="icon" type="image/x-icon" href="Ham and Cheese Pizza.jpg">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eee3ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    form {
      background: rgb(233, 187, 233);
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    input {
      width: 95%;
      padding: 12px;
      margin-bottom: 15px;
      border: 2px solid #661313;
      border-radius: 5px;
      font-size: 14px;
      background-color: rgb(247, 227, 202);
    }

    button {
  padding: 12px;
  width: 100%;
  background-color: #007bff;
  border: none;
  color: white;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}
  </style>
</head>
<body>
  <form method="POST">
    <h1>Create an Account</h1>
    <label for="fname">First Name</label>
    <input type="text" id="fname" name="fname" value="<?= $first_name; ?>" required placeholder="Enter your First Name">
    <label for="Lname">Last Name</label>
    <input type="text" id="Lname" name="Lname" value="<?= $last_name; ?>" required placeholder="Enter your Last Name">
    <label for="em">Email</label>
    <input type="email" id="em" name="em" value="<?= $email; ?>" required placeholder="Enter your Email">
    <label for="pass">Password</label>
    <input type="password" id="pass" name="pass" required placeholder="Enter your password">
    <label for="Cpass">Confirm Password</label>
    <input type="password" id="Cpass" name="Cpass" required placeholder="Enter your password">
    <button type="submit">Register</button>
    <a href="./login.php">
            <button type="button" id="createAccountButton" class="create-account-button">Login</button>
            </a> 
  </form>
</body>
</html>

<?php
}
?>
