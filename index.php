<?php
session_start();
$title = "Home";
require_once "./includes/header.php";
require_once "./db/conn.php";
?>

<div class="container form-div">
    <h2 class="clabel">Login/Signup</h2><br>
    <div class="d-flex flex-column justify-content-between align-items-center w-75 mx-auto">
        <a href="./login.php" class="w-100 center"><button class="btn btn-primary btn-lg cbtn">Login</button></a>
        <a href="./signup.php" class="w-100 center"><button class=" btn btn-secondary btn-lg mt-3 cbtn">Sign Up</button></a>
    </div>
</div>

<?php
require_once "./includes/footer.php";
?>