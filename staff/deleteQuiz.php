<?php
require_once "../db/conn.php";

function deleteQuiz(PDO $pdo, $id)
{
    $sql = "DELETE FROM quiz WHERE quiz_id=:quiz_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(["quiz_id" => $id]);
}

$id = $_GET['id'];
$del = deleteQuiz($pdo, $id);

if ($del) {
    header("location:index.php"); 
    exit();
} else {
    echo "Error";
}
?>