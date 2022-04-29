<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href= "style.css" rel = "stylesheet">

    <title>iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include "partials/_dbconnect.php" ?>
    <?php include "partials /_header.php" ?>
    <?php 
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` WHERE thread_id = $id;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_user_id = $row['thread_user_id'];

        //query the user table to find the name of the poster
        $sql3= "SELECT username FROM `users` WHERE sno = '$thread_user_id'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        $posted_by = $row3['username'];

    }   
    ?>
    <?php
    $showAlert= false;
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        $comment= $_POST['comment'];
        $sno = $_POST['sno'];

        $comment = str_replace("<", "&lt;", "$comment");
        $comment = str_replace(">", "&gt;", "$comment");

        $sql = "INSERT INTO `comments` ( `comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp());";
        $request = mysqli_query($conn, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your comment has been posted. Please wait for the OP to respond.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    }
    ?>
    <div class="container py-4">
        <div class="p-5 mb-4 bg-dark rounded-3 text-light">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"><?php echo $title; ?></h1>
                <p class="col-md-8 fs-4"><?php echo $desc; ?></p>
                <p class="col-md-8 fs-4">No Spam / Advertising / Self-promote in the forums.
                    Do not post copyright-infringing material.
                    Do not post “offensive” posts, links or images.
                    Do not cross post questions.
                    Do not PM users asking for help.
                    Remain respectful of other members at all times.</p>
                <p><em>Posted by : <?php echo $posted_by ; ?></em></p>
            </div>
        </div>
    </div>
    <?php 

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true ) {
        echo ' <div class="container">
        <h1 class="py-2">Post a comment.</h1>
        <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                    name="comment" style="height: 100px"></textarea>
                <label for="floatingTextarea2">Comment</label>
                <input type="hidden" name="sno" value = "'.$_SESSION["sno"].'">

            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>';
    }
    else{
        echo '<div class="container">
              <h1 class="py-2">Post a comment.</h1>
              <p class="lead">You need to login to comment.</p>
                
            </div>
    ';
    }
   
    ?>
   
    <div class="container long">
        <h1 class="py-2">Discussion</h1>
        <?php 
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `comments` WHERE thread_id = $id";
    $result = mysqli_query($conn, $sql);
    $noResult = true;

    while ($row = mysqli_fetch_assoc($result)) {
        $noResult = false;
        $id = $row['thread_id'];
        $content = $row['comment_content'];
        $by = $row['comment_by'];
        $comment_time = $row['comment_time']; 
        $thread_user_id = $row['comment_by'];
        $sql2 = "SELECT username FROM `users` WHERE sno = '$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
         
        echo ' <div class= "container"><div class="d-flex my-4">
                    <div class="flex-shrink-0">
                        <img src="/forum/images/user-icon.png" width="34px" alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                       <h5>'.$row2['username'].' at '.$comment_time.'</h5>
                        <p>'. $content . '</p>
                    </div>
                </div>
            </div></div>';
        }
        if($noResult){
            echo '<div class="container py-4">
            <div class="p-5 mb-4 bg-dark rounded-3 text-light">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">No Comments Found</h1>
                    <p class="col-md-8 fs-4">Be the first to comment.</p>
                </div>
            </div>
        </div>';
        }

        

  
    ?>

       
    <?php include "partials/_footer.php" ?>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>
<!-- INSERT INTO `threads` (`thread_id`, `thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('1', 'I don\'t understand python.', 'I don\'t understand databases. I don\'t understand databasesI don\'t understand databasesI don\'t understand databasesI don\'t understand databasesI don\'t understand databasesI don\'t understand databases,', '1', '0', current_timestamp()); -->