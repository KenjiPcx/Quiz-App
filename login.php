<?php
session_start();
$title = "Login";
require_once "./includes/header.php";
require_once "./db/conn.php";
$errors = array(
    "email" => "",
    "password" => ""
);
handleForm($pdo, $errors);
?>

<div class="container form-div">
    <h2 class="clabel">Login</h2>
    </br>
    <form method="POST">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" required>
            <div class="invalid-feedback <?php echo $errors["email"]; ?>">
                Account does not exist, please try again.
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
            <div class="invalid-feedback <?php echo $errors["password"]; ?>">
                Invalid password, please try again.
            </div>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" id="inlineRadio1" value="staff" name="userType" required>
            <label class="form-check-label" for="inlineRadio1">Staff</label>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" id="inlineRadio2" value="student" name="userType" required>
            <label class="form-check-label" for="inlineRadio2">Student</label>
        </div>
        <div id="emailHelp" class="form-text">Don't have an account? <a href="signup.php">Sign up here</a>.</div>
        </br>
        <div class="center">
            <button type="submit" class="btn btn-primary btn-lg cbtn">Login</button>
        </div>
    </form>
</div>

<?php
function handleForm(PDO $pdo, array &$errors)
{
    if ($_POST && isset($_POST["email"], $_POST["password"], $_POST["userType"])) {
        // Check account exists
        if ($_POST["userType"] == "student") {
            $sql = "SELECT student_id, first_name, email, password FROM student WHERE email=:email";
        } else {
            $sql = "SELECT author_id, first_name, email, password FROM author WHERE email=:email";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $_POST["email"]]);
        $user = $stmt->fetch();
        if (!$user) {
            $errors["email"] = "show";
        } else {
            // Check password match
            if (!password_verify($_POST["password"], $user->password)) {
                $errors["password"] = "show";
            }

            // Redirect
            if ($errors["email"] == "" && $errors["password"] == "") {
                $_SESSION["user"] = $user->first_name;
                $_SESSION["email"] = $user->email;
                if ($_POST["userType"] == "student") {
                    header('Location: ./students/index.php');
                    $_SESSION["userId"] = $user->student_id;
                } else {
                    header('Location: ./staff/index.php');
                    $_SESSION["userId"] = $user->author_id;
                }
                exit();
            }
        }
    }
}
?>

<?php
require_once "./includes/footer.php";
?>