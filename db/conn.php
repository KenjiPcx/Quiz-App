<?php
    $host = "127.0.0.1";
    $db = "quizdb";
    $user = "root";
    $password = "";
    $dsn = "mysql:host=$host;dbname=$db";

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        // echo "Connected";
    } catch (PDOException $e) {
        echo "QuizDb not found. Initializing Db...";
        initDb();
        initTables();
        echo '<script type="text/javascript">location.reload(true);</script>';
    }

    function initDb()
    {
        $pdo = new PDO("mysql:host=localhost", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS quizdb;";
        $pdo->query($sql);
    };

    function initTables()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=quizdb", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS author (
                    author_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    password VARCHAR(100) NOT NULL,
                    PRIMARY KEY (author_id)
                );";
        $pdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS quiz (
                    quiz_id INT UNSIGNED AUTO_INCREMENT,
                    quiz_name VARCHAR(100) NOT NULL,
                    author_id INT UNSIGNED NOT NULL,
                    available BOOLEAN,
                    duration INT DEFAULT NULL,
                    PRIMARY KEY (quiz_id),
                    FOREIGN KEY (author_id) REFERENCES author(author_id) ON DELETE RESTRICT ON UPDATE RESTRICT
                );";
        $pdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS student (
                    student_id INT UNSIGNED AUTO_INCREMENT,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(100) NOT NULL,
                    PRIMARY KEY (student_id)
                );";
        $pdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS attempt (
                    attempt_id INT UNSIGNED AUTO_INCREMENT,
                    student_id INT UNSIGNED,
                    quiz_id INT UNSIGNED,
                    date_of_attempt DATE NOT NULL,
                    score INT UNSIGNED NOT NULL DEFAULT 0,
                    PRIMARY KEY (attempt_id),
                    FOREIGN KEY (student_id) REFERENCES student(student_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                    FOREIGN KEY (quiz_id) REFERENCES quiz(quiz_id) ON DELETE RESTRICT ON UPDATE RESTRICT  
                );";
        $pdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS question (
                    question_id INT UNSIGNED AUTO_INCREMENT,
                    question VARCHAR(255) NOT NULL,
                    option1 VARCHAR(255) NOT NULL,
                    option2 VARCHAR(255) NOT NULL,
                    option3 VARCHAR(255) DEFAULT NULL,
                    option4 VARCHAR(255) DEFAULT NULL,
                    answer INT UNSIGNED NOT NULL,
                    quiz_id INT UNSIGNED NOT NULL,
                    PRIMARY KEY (question_id),
                    FOREIGN KEY (quiz_id) REFERENCES quiz(quiz_id) ON DELETE RESTRICT ON UPDATE RESTRICT
                );";
        $pdo->query($sql);
    };
?>