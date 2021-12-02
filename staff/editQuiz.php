<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";

$quiz_id = $_GET["id"];
editQuiz($pdo, $quiz_id);
?>

<div class="container w-50 quiz-div d-flex flex-column justift-content-start">
    <?php
    showQuiz($pdo, $_GET["id"]);
    ?>
    <div class="w-100 d-flex justify-content-center">
        <button class="btn btn-success mt-3 mb-5" data-bs-toggle='modal' data-bs-target='#newQuestionModal'>Add New Question</button>
    </div>
</div>

<div class="modal fade" id="editQuizModal" tabindex="-1" aria-labelledby="editQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuizModalLabel">Edit Quiz Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="./editQuiz.php?id=<?= $quiz_id; ?>">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="form-question" class="form-label">Quiz Name</label>
                        <input type="text" class="form-control" id="form-quiz-name" name="quiz_name" required>
                    </div>
                    <div class="mb-2">
                        <label for="exampleFormControlInput1" class="form-label">No time limit</label>
                        <input class="form-check-input" type="checkbox" value="1" id="noTimeLimit" onchange="hideDuration()" name="no_time_limit">
                    </div>
                    <div class="mb-2">
                        <label for="durationRange" class="form-label" id="durationRangeVal">Duration: 30 Minutes</label>
                        <input type="range" class="form-range" id="durationRange" min="30" max="180" step="15" value="30" onchange="updateDuration(this.value)" name="duration">
                    </div>
                    <div class="mb-2">
                        <label for="exampleFormControlInput1" class="form-label">Available</label>
                        <select class="form-select" name="available" required>
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="newQuestionModal" tabindex="-1" aria-labelledby="newQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newQuestionModalLabel">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="./newQuestion.php?id=<?php echo $quiz_id; ?>">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="form-question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="form-new-question" name="question" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option1" class="form-label">Option 1</label>
                        <input type="text" class="form-control" id="form-new-option1" name="option1" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option2" class="form-label">Option 2</label>
                        <input type="text" class="form-control" id="form-new-option2" name="option2" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option3" class="form-label">Option 3</label>
                        <input type="text" class="form-control" id="new-option3" name="option3" onchange="checkOption3()">
                    </div>
                    <div class="mb-2">
                        <label for="form-option4" class="form-label">Option 4</label>
                        <input type="text" class="form-control" id="new-option4" name="option4" onchange="checkOption4()">
                    </div>
                    <div class="mb-2">
                        <label for="form-answer" class="form-label">Answer</label>
                        <select class="form-select" name="answer">
                            <option value="1" selected>Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3" id="newSelect3">Option 3</option>
                            <option value="4" id="newSelect4">Option 4</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="./editQuestion.php?quiz_id=<?php echo $quiz_id; ?>">
                <div class="modal-body">
                    <div class="mb-2">
                        <input type="text" class="form-control" name="question_id" value="" id="question_id" readonly hidden>
                    </div>
                    <div class="mb-2">
                        <label for="form-question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="form-question" name="question" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option1" class="form-label">Option 1</label>
                        <input type="text" class="form-control" id="form-option1" name="option1" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option2" class="form-label">Option 2</label>
                        <input type="text" class="form-control" id="form-option2" name="option2" required>
                    </div>
                    <div class="mb-2">
                        <label for="form-option3" class="form-label">Option 3</label>
                        <input type="text" class="form-control" id="edit-option3" name="option3" onchange="checkEditOption3()">
                    </div>
                    <div class="mb-2">
                        <label for="form-option4" class="form-label">Option 4</label>
                        <input type="text" class="form-control" id="edit-option4" name="option4" onchange="checkEditOption4()">
                    </div>
                    <div class="mb-2">
                        <label for="form-answer" class="form-label">Answer</label>
                        <select class="form-select" name="answer">
                            <option value="1" selected>Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3" id="editSelect3">Option 3</option>
                            <option value="4" id="editSelect4">Option 4</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
/**
 * Saves the changes to the quiz to the database
*/
function editQuiz(PDO $pdo, $quiz_id)
{
    if (isset($_POST["quiz_name"], $_POST["duration"], $_POST["available"])) {
        if (isset($_POST["no_time_limit"])) {
            $_POST["duration"] = null;
        }

        $sql = "UPDATE quiz 
                SET quiz_name=:quiz_name, duration=:duration, available=:available 
                WHERE quiz_id=:quiz_id";
        $stmt = $pdo->prepare($sql);
        $update = $stmt->execute(["quiz_name" => $_POST["quiz_name"], "duration" => $_POST["duration"], "available" => $_POST["available"], "quiz_id" => $quiz_id]);

        if ($update) {
            header("location:editQuiz.php?id=$quiz_id");
            exit();
        } else {
            echo "Error";
        }
    }
}

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
    $available = $quiz_data->available ? "Yes" : "No";
    $duration = $quiz_data->duration != null ? "Duration: " . $quiz_data->duration . " minutes" : "No time limit";

    echo "<div class='card mb-5'>
            <div class='card-body'>
                <h1 class='card-title' id='quiz_name'>$quiz_data->quiz_name</h1>
                <p class='card-text'>Quiz Author: $author</p>
                <div class='d-flex justify-content-between align-items-center w-100'>
                    <p class='card-text mb-0'><small class='text-muted'>$duration</small></p>
                    <p class='card-text mb-0'><small class='text-muted'>Available: $available</small></p>
                </div>
                <div class='d-flex justify-content-between mt-2'>
                    <div class='w-75'>
                        <a role='button' href='./quiz.php?id=$quiz_id' class='btn btn-primary mr-3 px-3'>Preview Quiz</a>
                        <button type='button' class='btn btn-secondary px-3 ml-1' data-bs-toggle='modal' data-bs-target='#editQuizModal'>Edit Quiz Details</button>
                    </div>
                    <a role='button' href='./deleteQuiz.php?id=$quiz_id' class='btn btn-danger ml-3'>Delete Quiz</a>
                </div>
            </div>
        </div>";
}

/**
 * Prints all the question in cards
 */
function printQuestions($questions)
{
    $quiz_id = $_GET["id"];
    foreach ($questions as $key => $question) {
        $question_id = $question->question_id;
        $questionid = "question-" . $question_id;
        $option1id = "option1-" . $question_id;
        $option2id = "option2-" . $question_id;
        $option3id = $question->option3 ? "option3-" . $question_id : "";
        $option4id = $question->option4 ? "option4-" . $question_id : "";
        $answerid = "answer-" . $question_id;

        $number = $key + 1;
        $radio_group = "radio-" . $question_id;
        $option3 = $question->option3 ? formatRadio($question->option3, $radio_group, $option3id) : "";
        $option4 = $question->option4 ? formatRadio($question->option4, $radio_group, $option4id) : "";
        $answer = decodeAnswer($question);

        echo "<div class='card mb-3' id-$key>
                <h5 class='card-header'>$number.) <span id=$questionid>$question->question</span></h5>
                    <div class='card-body'>
                        <div class='form-check'>
                            <input class='form-check-input' type='radio' name=$radio_group id=$radio_group>
                            <label class='form-check-label' for=$radio_group id=$option1id>$question->option1</label>
                        </div>
                        <div class='form-check'>
                            <input class='form-check-input' type='radio' name=$radio_group id=$radio_group>
                            <label class='form-check-label' for=$radio_group id=$option2id>$question->option2</label>
                        </div>
                        $option3
                        $option4
                    </div>
                    <div class='d-flex justify-content-between mt-3 card-footer'>
                        <div class='mr-3' id=$answerid data-ans=$question->answer>Answer: <strong>$answer</strong></div>
                        <div>
                            <button type='button' class='btn btn-secondary btn-sm mx-3 px-3 ml-auto' data-bs-toggle='modal' data-bs-target='#editQuestionModal' data-id=$question->question_id>Edit</button>
                            <a role='button' href='deleteQuestion.php?id=$question->question_id&quiz_id=$quiz_id' class='btn btn-danger btn-sm'>Delete</a>
                        </div>
                    </div>
                </div>";
    }
}

/**
 * Parses question's index to its value
 */
function decodeAnswer(mixed $question)
{
    switch ($question->answer) {
        case 1:
            $answer = $question->option1;
            break;
        case 2:
            $answer = $question->option2;
            break;
        case 3:
            $answer = $question->option3;
            break;
        case 4:
            $answer = $question->option4;
            break;
        default:
            $answer = $question->option1;
    }

    return $answer;
}

/**
 * Dynamic radio button builder
 */
function formatRadio($option, $radio_group, $optionid)
{
    return "<div class='form-check'>
                <input class='form-check-input' type='radio' name=$radio_group id=$radio_group>
                <label class='form-check-label' for=$radio_group id=$optionid>$option</label>
            </div>";
}
?>

<?php
require_once "../includes/footer.php";
?>

<script type="text/javascript">
    const initEditQuizListener = () => {
        let modal = document.querySelector('#editQuizModal');
        modal.addEventListener('show.bs.modal', event => {
            console.log(1)
            let quiz_name = document.querySelector("#quiz_name").innerHTML;
            document.querySelector("#form-quiz-name").value = quiz_name;
        })
    }

    const initEditQuestionListener = () => {
        let modal = document.querySelector('#editQuestionModal');
        modal.addEventListener('show.bs.modal', event => {
            let id = event.relatedTarget.dataset.id;
            let id_input = document.querySelector("#question_id");
            let title = document.querySelector("#editQuestionModalLabel");

            id_input.value = id;
            title.innerHTML = "Editing Question of ID " + id;

            let question = document.querySelector(`#question-${id}`).innerHTML;
            let option1 = document.querySelector(`#option1-${id}`).innerHTML;
            let option2 = document.querySelector(`#option2-${id}`).innerHTML;
            console.log(option1)
            console.log(option2)
            let option3 = document.querySelector(`#option3-${id}`) ? document.querySelector(`#option3-${id}`).innerHTML : null;
            let option4 = document.querySelector(`#option4-${id}`) ? document.querySelector(`#option4-${id}`).innerHTML : null;
            let answer = document.querySelector(`#answer-${id}`).dataset.ans;

            document.querySelector("#form-question").value = question;
            document.querySelector("#form-option1").value = option1;
            document.querySelector("#form-option2").value = option2;
            document.querySelector("#form-option3").value = option3;
            document.querySelector("#form-option4").value = option4;
            document.querySelector("#form-answer").value = answer;
        })
    }

    const checkOption3 = () => {
        let option3 = document.querySelector("#new-option3").value;
        if (option3 === "") {
            document.querySelector("#newSelect3").style.display = "none";
        } else {
            document.querySelector("#newSelect3").style.display = "block";
        }
    }

    const checkOption4 = () => {
        let option3 = document.querySelector("#new-option4").value;
        if (option3 === "") {
            document.querySelector("#newSelect4").style.display = "none";
        } else {
            document.querySelector("#newSelect4").style.display = "block";
        }
    }

    const checkEditOption3 = () => {
        let option3 = document.querySelector("#edit-option3").value;
        if (option3 === "") {
            document.querySelector("#editSelect3").style.display = "none";
        } else {
            document.querySelector("#editSelect3").style.display = "block";
        }
    }

    const checkEditOption4 = () => {
        let option3 = document.querySelector("#edit-option4").value;
        if (option3 === "") {
            document.querySelector("#editSelect4").style.display = "none";
        } else {
            document.querySelector("#editSelect4").style.display = "block";
        }
    }

    const updateDuration = (val) => {
        document.querySelector('#durationRangeVal').value = val;
        document.querySelector('#durationRangeVal').innerHTML = "Duration: " + val + " Minutes";
    }

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

    initEditQuizListener();
    initEditQuestionListener();
    checkOption3();
    checkOption4();
    checkEditOption3();
    checkEditOption4();
</script>