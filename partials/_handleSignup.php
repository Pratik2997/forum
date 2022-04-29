<?php
$showError = "false";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    include "_dbconnect.php";
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['signupPassword'];
    $cpassword = $_POST['cpassword'];

    //check if the username exists

    $sql = "SELECT * FROM `users` WHERE  email = '$email'";
    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    $usersql = "SELECT * FROM `users` WHERE  username = '$username'";
    $result2 = mysqli_query($conn, $usersql);
    $numuser = mysqli_num_rows($result2);

    if(($numrows > 0) || ($numuser > 0)){
        $showError = "Email or username already in use";
    }
    else {
        if ($password==$cpassword) {
        $hash= password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`,`email`, `password`, `dt`) VALUES ('$username','$email', '$hash', current_timestamp())";
        $result = mysqli_query($conn, $sql);
         if ($result) {
           $showAlert= true;
           header("location: /forum/index.php?signupsuccess=true");
           exit();
        }
      }
        else{
          $showError = "Passwords do not match.";
        }
    }
    header("location: /forum/index.php?signupsuccess=false&error=$showError");
}

?>