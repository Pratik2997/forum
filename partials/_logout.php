<?php

session_start();
echo "Logging out...";

session_destroy();

header("location: /forum/index.php");

?>