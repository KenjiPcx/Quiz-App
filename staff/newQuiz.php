<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";

// When data is posted to itself
if (!empty($_POST)) {
    handleFormSubmit($pdo);
}
?>

<div class="container quiz-div w-50">
    <form method="POST">
        <div class='card mb-5'>
            <div class='card-body'>
                <h1 class='card-header mb-3'>New Quiz Details</h1>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Quiz Name</label>
                    <input type="text" class="form-control" id="exampleFormControlInput1" name="quiz_name" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">No time limit</label>
                    <input class="form-check-input" type="checkbox" value="1" id="noTimeLimit" onchange="hideDuration()" name="no_time_limit">
                </div>
                <div class="mb-3">
                    <label for="durationRange" class="form-label" id="durationRangeVal">Duration: 30 Minutes</label>
                    <input type="range" class="form-range" id="durationRange" min="30" max="180" step="15" value="30" onchange="updateDuration(this.value)" name="duration">
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Author ID</label>
                        <input type="text" class="form-control" readonly value="<?= $_SESSION["userId"]; ?>" name="author_id" required>
                    </div>
                    <div class="col form-check">
                        <label for="exampleFormControlInput1" class="form-label">Available</label>
                        <select class="form-select" name="available" required>
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-center">
            <button type="submit" class="btn btn-success btn-lg">Create New Quiz</button>
        </div>
    </form>
</div>

<?php
/**
 * Saves the quiz data to the database
 */
function handleFormSubmit(PDO $pdo)
{
    if (isset($_POST["no_time_limit"])) {
        $_POST["duration"] = NULL;
    }

    $sql = "INSERT INTO quiz (quiz_name, author_id, duration, available) 
            VALUES (:quiz_name, :author_id, :duration, :available)";
    $stmt = $pdo->prepare($sql);
    $insert = $stmt->execute(["quiz_name" => $_POST["quiz_name"], "author_id" => $_POST["author_id"], "duration" => $_POST["duration"], "available" => $_POST["available"]]);

    if ($insert) {
        echo "Success";
        $last_insert_id = $pdo->lastInsertId();
        header("location:editQuiz.php?id=$last_insert_id");
        exit();
    } else {
        echo "Failed";
    }
}
?>

<?php
require_once "../includes/footer.php";
?>

<script type="text/javascript">
    // Updates the range value
    const updateDuration = (val) => {
        document.querySelector('#durationRangeVal').value = val;
        document.querySelector('#durationRangeVal').innerHTML = "Duration: " + val + " Minutes";
    }

    // Hides range input when no time limit is selected
    const hideDuration = () => {
        let hide = document.querySelector('#noTimeLimit').checked;
        if (hide) {
            document.querySelector('#durationRangeVal').style.display = "none";
            document.querySelector('#durationRange').style.display = "none";
        } else {
            document.querySelector('#durationRangeVal').style.display = "block";
            document.querySelector('#durationRange').style.display = "block";
        }
    }
</script>