<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";

$quiz_id = $_GET["id"];
createNewQuestion($pdo, $quiz_id);
?>

<?php 
/**
 * Saves the new question to the database
 */
function createNewQuestion(PDO $pdo, $quiz_id) 
{
    if ($_POST["option3"] == "") {
        $_POST["option3"] = null;
    }
    if ($_POST["option4"] == "") {
        $_POST["option4"] = null;
    }

    $sql = "INSERT INTO question (question, option1, option2, option3, option4, answer, quiz_id)
            VALUES (:question, :option1, :option2, :option3, :option4, :answer, :quiz_id)";
    $stmt = $pdo->prepare($sql);
    $insert = $stmt->execute(["question" => $_POST["question"], "option1" => $_POST["option1"],"option2" => $_POST["option2"],"option3" => $_POST["option3"],"option4" => $_POST["option4"],"answer" => $_POST["answer"], "quiz_id" => $quiz_id]);

    if ($insert) {
        echo "Success";
        header("location:editQuiz.php?id=$quiz_id");
        exit();
    } else {
        echo "Failed to insert";
    }
}
?>

<?php
require_once "../includes/footer.php";
?>