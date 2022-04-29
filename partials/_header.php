<?php
session_start();

if(isset($_SESSION['loggedin']) && ($_SESSION['loggedin']==true)){
  $loggedin = true;
}
else{
  $loggedin = false;
}

echo '<div class="container-fluid bg-dark">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
  <a class="navbar-brand" href="/forum">iDiscuss</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/forum">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Top Categories
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

        $sql = "SELECT category_name, category_id FROM `categories` LIMIT 3";
        $result = mysqli_query($conn, $sql);
         while ($row = mysqli_fetch_assoc($result)) {
           echo '<li><a class="dropdown-item" href="threadlist.php?catid='.$row['category_id'].'">'.$row['category_name'].'</a></li>';
         }
          
          echo '
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
    </ul>
    <div class="mx-2 d-flex">
    <form class="d-flex" action = "search.php" method="get">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name = "search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>';

    if ($loggedin==true) {
     echo '<p class="text-light mb-0 pt-2 px-3">Welcome'.$_SESSION['email'].'</p><a class="btn btn-primary ms-2" href="/forum/partials/_logout.php">Logout</a>';
    }
    else{
      echo '<button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button> <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>';
    }
    
    
    echo'</div>    
    
  </div>
</div>
</nav>
</div>';

include 'partials/_loginModal.php';
include 'partials/_signupModal.php';

if (isset($_GET['signupsuccess']) && $_GET['signupsuccess']=="true") {
  echo '<div class="alert alert-success alert-dismissible fade show my-0" role="alert">
      <strong>Success!</strong> You are signed up. You can now log in.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if (isset($_GET['signupsuccess']) && $_GET['signupsuccess']=="false") {
  echo '<div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
      <strong>Failure!</strong>  Username or email already exists.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>