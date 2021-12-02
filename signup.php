<?php
session_start();
$title = "Sign Up";
require_once "./includes/header.php";
require_once "./db/conn.php";
$errors = array(
    "email" => "",
    "password" => ""
);
handleForm($pdo, $errors);
?>

<div class="container form-div">
    <h2 class="clabel">Sign Up</h2>
    </br>
    <form method="POST">
        <div class="row mb-3">
            <div class="col">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" aria-label="First name" id="firstName" name="firstName" required>
            </div>
            <div class="col">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" aria-label="Last name" id="lastName" name="lastName" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" required>
            <div class="invalid-feedback <?php echo $errors["email"]; ?>">
                Email already registered, please use another email.
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" aria-label="Password" id="password" name="password" required>
            </div>
            <div class="col">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="text" class="form-control" aria-label="Confirm Password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="invalid-feedback <?php echo $errors["password"]; ?>">
                Passwords don't match, please try again.
            </div>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="userType" id="inlineRadio1" value="author" required>
            <label class="form-check-label" for="inlineRadio1">Staff</label>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="userType" id="inlineRadio2" value="student" required>
            <label class="form-check-label" for="inlineRadio2">Student</label>
        </div>
        <div id="emailHelp" class="form-text">Already have an account? <a href="login.php">Login here</a>.</div>
        </br>
        <div class="center">
            <button type="submit" class="btn btn-primary btn-lg cbtn">Sign Up</button>
        </div>
    </form>
</div>

<?php
function validateForm(PDO $pdo, &$errors)
{
    // handle email validation
    if ($_POST["userType"] == "student") {
        $sql = "SELECT * FROM student WHERE email=:email";
    } else {
        $sql = "SELECT * FROM author WHERE email=:email";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["email" => $_POST["email"]]);
    $user = $stmt->fetch();
    if ($user) {
        $errors["email"] = "show";
    }

    // handle password validation
    if ($_POST["password"] != $_POST["confirmPassword"]) {
        $errors["password"] = "show";
    }
}

function handleForm(PDO $pdo, array &$errors)
{
    if ($_POST && isset($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["userType"])) {

        validateForm($pdo, $errors);

        // validation success
        if ($errors["email"] == "" && $errors["password"] == "") {
            $user = array();
            $user["firstName"] = $_POST["firstName"];
            $user["lastName"] = $_POST["lastName"];
            $user["email"] = $_POST["email"];
            $user["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);

            if ($_POST["userType"] == "student") {
                $sql = "INSERT INTO student (first_name, last_name, email, password) values (:firstName, :lastName, :email, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["firstName" => $user["firstName"], "lastName" => $user["lastName"], "email" => $user["email"], "password" => $user["password"]]);
                $_SESSION["userId"] = $pdo->lastInsertId();
                $_SESSION["user"] = $user["firstName"];
                $_SESSION["email"] = $user["email"];
                header('Location: ./students/index.php');
                exit();
            } else {
                $sql = "INSERT INTO author (first_name, last_name, email, password) values (:firstName, :lastName, :email, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["firstName" => $user["firstName"], "lastName" => $user["lastName"], "email" => $user["email"], "password" => $user["password"]]);
                $_SESSION["userId"] = $pdo->lastInsertId();
                $_SESSION["user"] = $user["firstName"];
                $_SESSION["email"] = $user["email"];
                header('Location: ./staff/index.php');
                exit();
            }
        }
    }
}
?>

<?php
require_once "./includes/footer.php";
?>