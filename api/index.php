<?php
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

function get_pdo() {
  require "dbConfig.php";
  
  $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
  $user = DB_USER;
  $pass = DB_PASS;
  
  return new PDO($dsn, $user, $pass);
}

function signup_user($userEmail, $userPassword) {
    $dbh = get_pdo();

    // Check for existing registration
    $stmt = $dbh->prepare("SELECT userID FROM users WHERE userEmail = :userEmail_value");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll();
  
    if(count($results) > 0) {
      echo "User Exists";
      die();
    }

    $stmt = $dbh->prepare("INSERT INTO users (userEmail, passwordHash, joinDate) VALUES (:userEmail_value, :passwordHash_value, :joinDate_value)");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->bindValue(":passwordHash_value", password_hash($userPassword, PASSWORD_BCRYPT), PDO::PARAM_INT);
    $stmt->bindValue(":joinDate_value", date("Y/m/d"), PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode($dbh->lastInsertId());
    die();
}

function login_user($userEmail, $userPassword) {
    $dbh = get_pdo();

    $stmt = $dbh->prepare("SELECT passwordHash, userID FROM users WHERE userEmail = :userEmail_value");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if (password_verify($userPassword, $results[0]["passwordHash"])) {
        return $results[0]["userID"];
    }
    return NULL;
}

function get_todos($userID) {
    $dbh = get_pdo();

    $stmt = $dbh->prepare("SELECT todoID, title, description, dueDate, complete FROM todos WHERE userID = :userID_value ORDER BY dueDate");
    $stmt->bindValue(":userID_value", $userID, PDO::PARAM_INT);
    $stmt->execute();
    
    $results = $stmt->fetchAll();

    return json_encode($results);
}

function make_todo($userID, $title, $description = NULL, $dueDate = NULL) {
    $dbh = get_pdo();

    $stmt = $dbh->prepare("INSERT INTO todos (userID, title, description, dueDate) VALUES (:userID_value, :title_value, :description_value, :dueDate_value)");
    $stmt->bindValue(":userID_value", $userID, PDO::PARAM_INT);
    $stmt->bindValue(":title_value", $title, PDO::PARAM_STR);
    $stmt->bindValue(":description_value", $description, PDO::PARAM_STR);
    $stmt->bindValue(":dueDate_value", $dueDate, PDO::PARAM_STR);
    $stmt->execute();

    return;
}

function change_complete_status_todo($todoID, $complete_status) {
    $dbh = get_pdo();

    $stmt = $dbh->prepare("UPDATE todos SET completed = :complete_value WHERE todoID = :todoID_value");
    $stmt->bindValue(":complete_value", $complete_status, PDO::PARAM_INT);
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    $stmt->execute();

    return;
}

function update_todo($todoID, $title = NULL, $description = NULL, $dueDate = NULL) {
    $dbh = get_pdo();

    $query = "UPDATE todos SET" . "" . $title == NULL ? "" : " title = :title_value " . "" . $description == NULL ? "" : " description = :description_value " . "" . $dueDate == NULL ? "" : " dueDate = :dueDate_value " . "" . " WHERE todoID = :todoID_value" ;
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    if ($title != NULL) {
        $stmt->bindValue(":title_value", $title, PDO::PARAM_STR);
    }
    if ($description != NULL) {
        $stmt->bindValue(":description_value", $description, PDO::PARAM_STR);
    }
    if ($dueDate != NULL) {
        $stmt->bindValue(":dueDate_value", $dueDate, PDO::PARAM_STR);
    }
    $stmt->execute();

    return;
}

function delete_todo($todoID) {
    $dbh = get_pdo();

    $stmt = $dbh->prepare("DELETE FROM todos WHERE todoID = :todoID_value");
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    $stmt->execute();

    return;
}

// sign up
if ($_REQUEST["action"] == "signup") {
    if($_REQUEST["user_Email"] && $_REQUEST["user_Password"]) {
        echo signup_user($_REQUEST["user_Email"], $_REQUEST["user_Password"]);
        die();
    }
    echo "Inavlid Params";
    die();
}
// log in
if ($_REQUEST["action"] == "login") {
    if ($_REQUEST["user_Email"] && $_REQUEST["user_Password"]) {
        echo login_user($_REQUEST["user_Email"], $_REQUEST["user_Password"]);
        die();
    }
    echo "Invalid Params";
    die();
}

// get todos
if ($_REQUEST["action"] == "getTodos") {
    if($_REQUEST["userID"]) {
        echo $getTodos($_REQUEST["userID"]);
        die();
    }
    echo "Invalid Params";
    die();
}

// make todo
if($_REQUEST["action"] == "makeTodo") {
    echo $makeTodo($_REQUEST["userID"], $_REQUEST["title"], isset($_REQUEST["description"]) ? $_REQUEST["description"] : NULL, isset($_REQUEST["dueDate"]) ? $_REQUEST["dueDate"] : NULL);
    die();
}
// complete todo
if($_REQUEST["action"] == "changeCompleteStatusTodo") {
    $change_complete_status_todo($_REQUEST["todoID"], $_REQUEST["completeStatus"]);
    die();
}
// update todo
if($_REQUEST["action"] == "updateTodo") {
    $update_todo($_REQUEST["userID"], isset($_REQUEST["title"]) ? $_REQUEST["title"] : NULL, isset($_REQUEST["description"]) ? $_REQUEST["description"] : NULL, isset($_REQUEST["dueDate"]) ? $_REQUEST["dueDate"] : NULL);
    die();
}
// delete todo
if($_REQUEST["action"] == "deleteTodo") {
    $delete_todo($_REQUEST["todoID"]);
    die();
}

?>
