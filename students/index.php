<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";
?>

<div class="container quiz-div w-50">
    <div class="d-flex justify-content-center">
        <h1>Quizzes</h1>
    </div>
    <br>
    <br>
    <ol class="list-group questions-div list-group-numbered mb-5">
        <?php showQuizList($pdo); ?>
    </ol>
</div>

<?php
/**
 * Prints the available quizzes into a list
 */
function showQuizList(PDO $pdo)
{
    $sql = "SELECT quiz.*, author.first_name, author.last_name
            FROM quiz INNER JOIN author ON quiz.author_id = author.author_id
            WHERE quiz.available=1";
    $stmt = $pdo->query($sql);
    $quizzes = $stmt->fetchAll();

    foreach ($quizzes as $quiz) {
        $author = "$quiz->first_name $quiz->last_name";
        $duration = $quiz->duration != null ? "Duration: " . $quiz->duration . " minutes" : "No time limit";

        echo "<li class='list-group-item'>
                <a href='quiz.php?id=$quiz->quiz_id'>$quiz->quiz_name</a>
                    <div class='text-muted d-flex justify-content-between'>
                        <small>Author: $author</small>
                        <small>$duration</small>
                    </div>
                </li>";
    }
}
?>

<?php
require_once "../includes/footer.php";
?>