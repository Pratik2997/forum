<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="style.css" rel="stylesheet">

    <title>iDiscuss - Coding Forums</title>
</head>

<body>
    <?php include "partials/_dbconnect.php" ?>
    <?php include "partials/_header.php" ?>
    <?php 
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` WHERE category_id = $id;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $catname = $row['category_name'];
        $catdesc = $row['category_description'];
    }   
    ?>
    <?php
    $showAlert= false;
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        $trtitle= $_POST['title'];
        $trdesc= $_POST['description'];
        $sno = $_POST['sno'];

        // to prevent from XSS attack
        $trtitle = str_replace("<", "&lt;", "$trtitle");
        $trtitle = str_replace(">", "&gt;", "$trtitle");

        $trdesc = str_replace("<", "&lt;", "$trdesc");
        $trdesc = str_replace(">", "&gt;", "$trdesc");

        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$trtitle', '$trdesc', '$id', '$sno', current_timestamp())";
        $request = mysqli_query($conn, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your question has been posted. Please wait for the community to respond.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    }

    ?>

    <div class="container py-4">
        <div class="p-5 mb-4 bg-dark rounded-3 text-light">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Welcome to <?php echo $catname; ?> forum.</h1>
                <p class="col-md-8 fs-4"><?php echo $catdesc; ?></p>
                <p class="col-md-8 fs-4">No Spam / Advertising / Self-promote in the forums.
                    Do not post copyright-infringing material.
                    Do not post “offensive” posts, links or images.
                    Do not cross post questions.
                    Do not PM users asking for help.
                    Remain respectful of other members at all times.</p>
                <button class="btn btn-success btn-lg" type="button">Learn More</button>
            </div>
        </div>
    </div>
    <?php 

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true ) {
        echo '<div class="container">
        <h1 class="py-2">Start a question</h1>
        <form action="'.$_SERVER["REQUEST_URI"].'" method="post">
            <div class="mb-3">
                <label for="exampleInputtitle1" class="form-label">Problem Title</label>
                <input type="text" class="form-control" id="title" aria-describedby="titleHelp" name="title">
                <div id="titleHelp" class="form-text">Keep a short and sweet title.</div>
            </div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                    name="description" style="height: 100px"></textarea>
                <label for="floatingTextarea2">Additional information</label>
                <input type="hidden" name="sno" value = "'.$_SESSION["sno"].'">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>';
    }
    else{
        echo '<div class="container">
        <h1 class="py-2">Start a question</h1>
                <p class="lead">You need to login to post a question.</p>

            </div>
    ';
    }
   
    ?>

    <div class="container">
        <h1 class="py-2">Browse Questions</h1>
        <?php 
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `threads` WHERE thread_cat_id = $id";
    $result = mysqli_query($conn, $sql);
    $noResult = true;
    while ($row = mysqli_fetch_assoc($result)){
        $noResult = false;
        $tid = $row['thread_id'];
        $ttitle = $row['thread_title'];
        $tdesc = $row['thread_desc'];
        $comment_time = $row['timestamp'];
        $thread_user_id = $row['thread_user_id'];
        $sql2 = "SELECT username FROM `users` WHERE sno = '$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        echo ' <div class = "container"><div class="d-flex my-4">
                    <div class="flex-shrink-0">
                        <img src="images/user-icon.png" width="34px" alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6>'.$row2['username'].' at '.$comment_time.'</h6>
                        <h5><a href = "thread.php/?threadid='.$tid.'" class="text-decoration-none text-dark">' . $ttitle .'</a></h5>
                        <p>'. $tdesc . '</p>
                    </div>
                </div>
            </div></div>';

    }   
    if ($noResult) {
        echo "No results found. Be the first person to ask a question";
    }
    ?>
    <?php 
    
    $id = $_GET['catid'];

    echo '<a href="threads.php/?catid='.$id.'&page=" class="btn btn-primary"></a>';

    ?>
   
       


    </div>

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