<?php
session_start();
$title = "Quiz";
require_once "./header.php";
require_once "../db/conn.php";
?>

<div class="container quiz-div">
    <ul class="list-group questions-div list-group-numbered">
        <?php showQuestionsList($pdo) ?>
    </ul>
</div>

<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="./editQuestion.php">
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
                        <input type="text" class="form-control" id="form-option3" name="option3">
                    </div>
                    <div class="mb-2">
                        <label for="form-option4" class="form-label">Option 4</label>
                        <input type="text" class="form-control" id="form-option4" name="option4">
                    </div>
                    <div class="mb-2">
                        <label for="form-answer" class="form-label">Answer</label>
                        <input type="text" class="form-control" id="form-answer" name="answer" required>
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
function showQuestionsList(PDO $pdo)
{
    $sql = "SELECT * FROM question";
    $stmt = $pdo->query($sql);
    $questions = $stmt->fetchAll();

    foreach ($questions as $key => $val) {
        $collapse = "collapse" . $key;
        $id = $val->question_id;
        $option1id = "option1-" . $id;
        $option2id = "option2-" . $id;
        $options = "<li id=$option1id>$val->option1</li>
                    <li id=$option2id>$val->option2</li>";
        if (isset($val->option3)) {
            $option3id = "option3-" . $id;
            $options = $options . "\n<li id=$option3id>$val->option3</li>";
        }
        if (isset($val->option4)) {
            $option4id = "option4-" . $id;
            $options = $options . "\n<li id=$option4id>$val->option4</li>";
        }
        printCard($val, $collapse, $options);
    }
}

function printCard(mixed $val, $collapse, $options)
{
    $id = $val->question_id;
    $questionid = "question-" . $id;
    $answerid = "answer-" . $id;
    $answer = decodeAnswer($val);

    echo "<li class='list-group-item d-flex' role='button' data-bs-toggle='collapse' href=#$collapse>
                <div class='ms-2 me-auto'>
                    <div class='fw-bold' id=$questionid>$val->question</div> 
                </div>
            </li>
            <div class='collapse' id=$collapse>
                <div class='card card-body'>
                    <ol type='a'>$options</ol>
                    <div class='d-flex justify-content-between mt-3'>
                        <div class='mr-3' id=$answerid data-ans=$val->answer>Answer: <strong>$answer</strong></div>
                        <div>
                            <button type='button' class='btn btn-secondary btn-sm mx-3 px-3 ml-auto' data-bs-toggle='modal' data-bs-target='#editQuestionModal' data-id=$id>Edit</button>
                            <a role='button' href='deleteQuestion.php?id=$id' class='btn btn-danger btn-sm'>Delete</a>
                        </div>
                    </div>
                </div>
            </div>";
}

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
?>

<?php
require_once "../includes/footer.php";
?>

<script type="text/javascript">
    let modal = document.querySelector('#editQuestionModal')
    modal.addEventListener('show.bs.modal', event => {
        let id = event.relatedTarget.dataset.id;
        let id_input = document.querySelector("#question_id");
        let title = document.querySelector("#editQuestionModalLabel");

        id_input.value = id;
        title.innerHTML = "Editing Question of ID " + id;

        let question = document.querySelector(`#question-${id}`).innerHTML;
        let option1 = document.querySelector(`#option1-${id}`).innerHTML;
        let option2 = document.querySelector(`#option2-${id}`).innerHTML;
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
</script>