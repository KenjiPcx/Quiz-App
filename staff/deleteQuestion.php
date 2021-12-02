<?php
require_once "../db/conn.php";

function deleteQuestion(PDO $pdo, $id)
{
    $sql = "DELETE FROM question WHERE question_id=:question_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(["question_id" => $id]);
}

$id = $_GET['id'];
$del = deleteQuestion($pdo, $id);

if ($del) {
    $quiz_id = $_GET["quiz_id"];
    header("location:editQuiz.php?id=$quiz_id"); 
    exit();
} else {
    echo "Error";
}
?>