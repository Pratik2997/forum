<?php
$showError = "false";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include '_dbconnect.php';
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $sql = "Select * from users where email='$email'";
    $result = mysqli_query($conn, $sql);
    $numRows = mysqli_num_rows($result);
    if($numRows==1){
        $row = mysqli_fetch_assoc($result); 
        $username = $row['username'];
        if(password_verify($password, $row['password'])){
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['sno'] = $row['sno'];
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $row['username'];
            echo "logged in". $email;
        } 
        header("Location: /forum/index.php");  
    }
    header("Location: /forum/index.php");  
}

?>
