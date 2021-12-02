<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";

$quiz_id = $_GET["id"];
?>

<div class="container quiz-div w-50">
    <div class="mb-5">
        <?php
        showQuiz($pdo, $_GET["id"]);
        ?>
    </div>
</div>

<?php
/**
 * Prints the quiz
 */
function showQuiz(PDO $pdo, $quiz_id)
{
    $quiz_data = getQuizData($pdo, $quiz_id);
    printQuizDetails($pdo, $quiz_data);

    $questions = getQuestions($pdo, $quiz_id);
    printQuestions($questions);
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
    $quiz_id = $_GET["id"];
    $author = getAuthor($pdo, $quiz_data->author_id);
    $duration = $quiz_data->duration != null ? "Duration: " . $quiz_data->duration . " minutes" : "No time limit";
    $available = $quiz_data->available ? "Yes" : "No";

    echo "<div class='card mb-5'>
            <div class='card-body'>
                <h1 class='card-title'>$quiz_data->quiz_name</h1>
                <p class='card-text'>Quiz Author: $author</p>
                <div class='d-flex justify-content-between align-items-center w-100'>
                    <p class='card-text mb-0'><small class='text-muted'>$duration</small></p>
                    <p class='card-text mb-0'><small class='text-muted'>Available: $available</small></p>
                </div>
                <a role='button' href='./editQuiz.php?id=$quiz_id' class='btn btn-secondary mt-2 px-3 ml-auto'>Edit Quiz</a>
            </div>
        </div>";
}

/**
 * Prints all the question in cards
 */
function printQuestions($questions)
{
    foreach ($questions as $key => $question) {
        $number = $key + 1;
        $radio_group = "radio" . $number;
        $option1 = formatRadio($question->option1, $radio_group);
        $option2 = formatRadio($question->option2, $radio_group);
        $option3 = $question->option3 ? formatRadio($question->option3, $radio_group) : "";
        $option4 = $question->option4 ? formatRadio($question->option4, $radio_group) : "";
        echo "<div class='card mb-3' id-$key>
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
function formatRadio($option, $radio_group)
{
    return "<div class='form-check'>
                <input class='form-check-input' type='radio' name=$radio_group id=$radio_group>
                <label class='form-check-label' for=$radio_group>
                    $option
                </label>
            </div>";
}
?>

<?php
require_once "../includes/footer.php";
?>