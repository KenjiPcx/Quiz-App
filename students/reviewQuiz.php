<?php
session_start();
$title = "History";
require_once "./header.php";
require_once "../db/conn.php";

$quiz_id = $_GET["id"];
$quiz_data = getQuizData($pdo, $quiz_id);
$questions = getQuestions($pdo, $quiz_id);

?>

<div class="container quiz-div w-50">
    <?php
    $score = 0;
    $wrong_ids = array();
    $no_of_questions = count($questions);
    // Dynamic answer checker from different redirects
    $answers = isset($_GET["attempt_id"]) ? getAttemptAnswers($pdo) : $_POST;
    checkAnswers($questions, $answers, $wrong_ids, $score);
    // If it is not redirected from finishing a quiz
    if (!empty($_POST)) {
        $attempt_id = handleSubmitScore($pdo, $quiz_id, $score, $no_of_questions);
        handleSubmitAnswers($pdo, $attempt_id);
    }
    
    printQuizDetails($pdo, $quiz_data);
    printResults($score, $no_of_questions);
    printQuestions($questions, $answers, $wrong_ids);
    ?>
    <div class="w-100 d-flex justify-content-center my-5">
        <a role="button" href=<?= isset($_GET["attempt_id"]) ? "history.php" : "index.php"; ?> class="btn btn-primary btn-lg">Done</a>
    </div>
</div>

<?php
/**
 * Gets student's answers from an attempt
 */
function getAttemptAnswers(PDO $pdo)
{
    $sql = "SELECT * FROM student_answer WHERE attempt_id=:attempt_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["attempt_id" => $_GET["attempt_id"]]);
    $answers = $stmt->fetchAll();

    $res_answers = array();
    foreach ($answers as $answer) {
        $answer_key = "radio-" . $answer->question_id;
        $res_answers[$answer_key] = $answer->chosen_answer;
    }

    return $res_answers;
}

/**
 * Checks the student's answers identify wrong answer ids and calculate their score
 */
function checkAnswers($questions, &$data, &$wrong_ids, &$score)
{
    foreach ($questions as $question) {
        $ans_key = "radio-" . $question->question_id;
        if (isset($data[$ans_key]) && $data[$ans_key] == $question->answer) {
            $score++;
        } else if (isset($data[$ans_key]) && $data[$ans_key] != null) {
            $wrong_ids[$ans_key] = $data[$ans_key];
        } else {
            $data[$ans_key] = null;
        }
    }
}

/**
 * Prints the attempt's results in a card
 */
function printResults($score, $no_of_questions)
{
    $redirect = isset($_GET["attempt_id"]) ? "history.php" : "index.php";
    $date_attempted = date('d/m/Y');
    echo "<div class='card mb-5'>
            <div class='card-body'>
                <h1 class='card-title'>Results</h1>
                <h5 class='card-text'>Score: $score/$no_of_questions</h5>
                <h6 class='text-muted'>Date attempted: $date_attempted</h6>
                <div class='d-flex justify-content-between align-items-center w-100'>
                    <a role='button' href=$redirect class='btn btn-primary'>Done</a>
                </div>
            </div>
        </div>";
}

/**
 * Sends the score and attempt data to the database
 */
function handleSubmitScore(PDO $pdo, $quiz_id, $score, $no_of_questions)
{
    $score = ($score * 100) / $no_of_questions;
    $sql = "INSERT INTO attempt (student_id, quiz_id, score) VALUES (:student_id, :quiz_id, :score)";
    $stmt = $pdo->prepare($sql);
    $insert = $stmt->execute(["student_id" => $_SESSION["userId"], "quiz_id" => $quiz_id, "score" => $score]);

    if (!$insert) {
        echo "Fail";
    }

    return $pdo->lastInsertId();
}

/**
 * Sends the student's answers to the database
 */
function handleSubmitAnswers(PDO $pdo, $attempt_id)
{
    $sql = "INSERT INTO student_answer (attempt_id, question_id, chosen_answer)
            VALUES (:attempt_id, :question_id, :chosen_answer)";
    $stmt = $pdo->prepare($sql);

    foreach ($_POST as $key => $answer) {
        $question_id = str_replace("radio-", "", $key);
        $stmt->execute(["attempt_id" => $attempt_id, "question_id" => $question_id, "chosen_answer" => $answer]);
    }
}

/**
 * Gets quiz details from database
 */
function getQuizData(PDO $pdo, $quiz_id)
{
    $sql = "SELECT * FROM quiz WHERE quiz_id=:quiz_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["quiz_id" => $quiz_id]);
    return  $stmt->fetch();
}

/**
 * Gets questions data from database
 */
function getQuestions(PDO $pdo, $quiz_id)
{
    $sql = "SELECT * FROM question WHERE quiz_id=:quiz_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["quiz_id" => $quiz_id]);
    return $stmt->fetchAll();
}

/**
 * Gets the quiz author's name
 */
function getAuthor(PDO $pdo, $author_id)
{
    $sql = "SELECT first_name, last_name FROM author WHERE author_id=:author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["author_id" => $author_id]);
    $author = $stmt->fetch();
    return $author->first_name . " " . $author->last_name;
}

/**
 * Prints the quiz details in a card
 */
function printQuizDetails(PDO $pdo, $quiz_data)
{
    $author = getAuthor($pdo, $quiz_data->author_id);
    $available = $quiz_data->available ? "Yes" : "No";
    echo "<div class='card mb-5'>
            <div class='card-body'>
                <h1 class='card-title'>$quiz_data->quiz_name</h1>
                <p class='card-text'>Quiz Author: $author</p>
                <div class='d-flex justify-content-between align-items-center w-100'>
                    <p class='card-text mb-0'><small class='text-muted'>Duration: $quiz_data->duration minutes</small></p>
                    <p class='card-text mb-0'><small class='text-muted'>Available: $available</small></p>
                </div>
            </div>
        </div>";
}

/**
 * Prints all the question in cards
 */
function printQuestions($questions, $answers, $wrong_ids)
{
    foreach ($questions as $key => $question) {
        $number = $key + 1;
        $radio_group = "radio-" . $question->question_id;
        $option1 = formatRadio($answers, $question->option1, $radio_group, 1, $wrong_ids, $question->answer);
        $option2 = formatRadio($answers, $question->option2, $radio_group, 2, $wrong_ids, $question->answer);
        $option3 = $question->option3 ? formatRadio($answers, $question->option3, $radio_group, 3, $wrong_ids, $question->answer) : "";
        $option4 = $question->option4 ? formatRadio($answers, $question->option4, $radio_group, 4, $wrong_ids, $question->answer) : "";

        echo "<div class='card mb-3 ' id-$key>
                <h5 class='card-header'>$number.) $question->question</h5>
                    <div class='card-body'>
                        $option1
                        $option2
                        $option3
                        $option4
                    </div>
                </div>";
    }
}

/**
 * Dynamic radio button builder
 */
function formatRadio($answers, $option, $radio_group, $index, $wrong_ids, $answer)
{
    $show_res = "";
    if (isset($wrong_ids[$radio_group])) {
        if ($wrong_ids[$radio_group] == $index) {
            $show_res = "text-danger";
        }
    } else if ($answers[$radio_group] != null && $index == $answer) {
        $show_res = "text-success";
    }

    return "<div class='form-check'>
                <input class='form-check-input' type='radio' name=$radio_group id=$radio_group value=$index>
                <label class='form-check-label $show_res' for=$radio_group>$option</label>
            </div>";
}
?>

<?php
require_once "../includes/footer.php";
?>