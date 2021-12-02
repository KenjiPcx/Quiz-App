<?php
session_start();
$title = "History";
require_once "./header.php";
require_once "../db/conn.php";
?>

<div class="container quiz-div w-50">
    <div class="d-flex justify-content-center">
        <h1>Past Quiz Attempts</h1>
    </div>
    <br>
    <br>
    <ol class="list-group questions-div list-group-numbered mb-5">
        <?php showHistoryList($pdo); ?>
    </ol>
</div>

<?php
function showHistoryList(PDO $pdo)
{
    $sql = "SELECT attempt.*, quiz.quiz_name 
            FROM attempt INNER JOIN quiz 
            ON attempt.quiz_id = quiz.quiz_id 
            WHERE student_id=:student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["student_id" => $_SESSION["userId"]]);
    $quizzes = $stmt->fetchAll();

    foreach ($quizzes as $quiz) {
        echo "<li class='list-group-item'>
                <a href='reviewQuiz.php?id=$quiz->quiz_id&attempt_id=$quiz->attempt_id'>$quiz->quiz_name</a>
                <div class='text-muted d-flex justify-content-between'>
                    <small>Score: $quiz->score</small>
                    <small>Date attempted: $quiz->date_of_attempt</small>
                </div>
            </li>";
    }
}
?>

<?php
require_once "../includes/footer.php";
?>