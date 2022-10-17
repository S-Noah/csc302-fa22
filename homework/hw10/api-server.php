<?php
header('Content-type: application/json');

// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

// TODO Change this as needed. SQLite will look for a file with this name, or
// create one if it can't find it.
$dbName = 'data.db';

// Leave this alone. It checks if you have a directory named www-data in
// you home directory (on a *nix server). If so, the database file is
// sought/created there. Otherwise, it uses the current directory.
// The former works on digdug where I've set up the www-data folder for you;
// the latter should work on your computer.
$matches = [];
preg_match('#^/~([^/]*)#', $_SERVER['REQUEST_URI'], $matches);
$homeDir = count($matches) > 1 ? $matches[1] : '';
$dataDir = "/home/$homeDir/www-data";
if(!file_exists($dataDir)){
    $dataDir = __DIR__;
}
$dbh = new PDO("sqlite:$dataDir/$dbName")   ;
// Set our PDO instance to raise exceptions when errors are encountered.
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Put your other code here.

createTables();

// Handle incoming requests.
if(array_key_exists('action', $_POST)){
    $action = $_POST['action'];
    if($action == 'add-quizitem'){
        addQuizItem($_POST);
    } else if($action == 'get-quizitems'){
        getTable('QuizItems');
    } else if($action == 'remove-quizitem'){
        removeQuizItem($_POST);
    } else if($action == 'update-quizitem'){
        updateQuizItem($_POST);
    } else {
        echo json_encode([
            'success' => false, 
            'error' => 'Invalid action: '. $action
        ]);
    }
}


function createTables(){
    global $dbh;

    try{
        // Create the QuizItems table.
        $dbh->exec('create table if not exists QuizItems('. 
            'id integer primary key autoincrement, '. 
            'question text, answer text, created_at datetime default(datetime()), updated_at datetime default(datetime()))');

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error creating the tables: $e"
        ]);
    }
}

function addQuizItem($data){
    global $dbh;

    try {
        $statement = $dbh->prepare('insert into QuizItems(question, answer) '.
            'values (:question, :answer)');
        $statement->execute([
            ':question' => $data['question'], 
            ':answer'  => $data['answer']]);

        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error adding a book: $e"
        ]);
    }
}

function removeQuizItem($data){
    global $dbh;

    try {
        $statement = $dbh->prepare('delete from QuizItems where id = :id');
        $statement->execute([
            ':id' => $data['quizitem-id']]);

        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error adding a book: $e"
        ]);
    }
}  

function updateQuizItem($data){
    global $dbh;

    try {
        $statement = $dbh->prepare('update QuizItems set question = :question, answer = :answer, updated_at = datetime() where id = :id');
        $statement->execute([
            ':id' => $data['quizitem-id'],
            ':question' => $data['question'], 
            ':answer'  => $data['answer']]);

        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error adding a book: $e"
        ]);
    }
}  

function getTableRow($table, $data){
    global $dbh;
    try {
        $statement = $dbh->prepare("select * from $table where id = :id");
        $statement->execute([':id' => $data['id']]);
        // Use fetch here, not fetchAll -- we're only grabbing a single row, at 
        // most.
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $row]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error fetching rows from table $table: $e"
        ]);
    }
}

function getTable($table){
    global $dbh;
    try {
        $statement = $dbh->prepare("select * from $table");
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error fetching rows from table $table: $e"
        ]);
    }
}
?>