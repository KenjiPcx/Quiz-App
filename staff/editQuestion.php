<?php
require_once "../db/conn.php";

function editQuestion(PDO $pdo, array $data)
{
    if ($data["option3"] == "") {
        $data["option3"] = null;
    }
    if ($data["option4"] == "") {
        $data["option4"] = null;
    }

    $sql = "UPDATE question 
            SET question=:question, option1=:option1, option2=:option2, option3=:option3, option4=:option4, answer=:answer 
            WHERE question_id=:question_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

$update = editQuestion($pdo, $_POST);

if ($update) {
    if (isset($_GET["quiz_id"])) {
        $quiz_id = $_GET["quiz_id"];
        header("location:editQuiz.php?id=$quiz_id");
    } else {
        header("location:questions.php"); 
    }
    exit();
} else {
    echo "Error";
}
