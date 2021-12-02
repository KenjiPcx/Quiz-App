<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";
?>

<div class="container quiz-div">
    <div class="d-flex justify-content-center">
        <a href="./newQuiz.php" role="button" class="btn btn-success">New Quiz</a>
    </div>
    <br>
    <br>
    <ol class="list-group questions-div list-group-numbered mb-5">
        <?php showQuizList($pdo); ?>
    </ol>
</div>

<?php
/**
 * Prints the quizzes in a list
 */
function showQuizList(PDO $pdo)
{
    $sql = "SELECT * FROM quiz";
    $stmt = $pdo->query($sql);
    $quizzes = $stmt->fetchAll();
    foreach ($quizzes as $quiz) {
        $available = $quiz->available == 1 ? "Yes" : "No";
        $duration = $quiz->duration != null ? "Duration: " . $quiz->duration : "No time limit";

        echo "<li class='list-group-item'>
                <a href='quiz.php?id=$quiz->quiz_id'>$quiz->quiz_name</a>
                    <div class='text-muted d-flex justify-content-between'>
                        <small>Available: $available</small>
                        <small>$duration</small>
                    </div>
                </li>";
    }
}
?>

<?php
require_once "../includes/footer.php";
?>