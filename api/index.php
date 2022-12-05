<?php
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

function get_pdo()
{
    require "dbConfig.php";

    $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;
    $user = DB_USER;
    $pass = DB_PASS;

    return new PDO($dsn, $user, $pass);
}

function signupUser($userEmail, $userPassword)
{
    $dbh = get_pdo();

    // Check for existing registration
    $stmt = $dbh->prepare("SELECT userID FROM users WHERE userEmail = :userEmail_value");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if (count($results) > 0) {
        echo json_encode("User Exists");
        die();
    }

    $stmt = $dbh->prepare("INSERT INTO users (userEmail, passwordHash, joinDate) VALUES (:userEmail_value, :passwordHash_value, :joinDate_value)");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->bindValue(":passwordHash_value", password_hash($userPassword, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $stmt->bindValue(":joinDate_value", date("Y/m/d"), PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode($dbh->lastInsertId());
    die();
}

function loginUser($userEmail, $userPassword)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("SELECT passwordHash, userID FROM users WHERE userEmail = :userEmail_value");
    $stmt->bindValue(":userEmail_value", $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll();

    if (password_verify($userPassword, $results[0]["passwordHash"])) {
        return json_encode($results[0]["userID"]);
    }
    return json_encode("Invalid Credentials");
}

function getTodos($userID)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("SELECT todoID, title, description, dueDate, complete FROM todos WHERE userID = :userID_value ORDER BY dueDate");
    $stmt->bindValue(":userID_value", $userID, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $json = array();

    foreach ($results as $result) {
        array_push($json, ["todoID" => $result["todoID"], "title" => $result["title"], "description" => $result["description"], "dueDate" => $result["dueDate"], "complete" => $result["complete"] == "0" ? false : true]);
    }
    return json_encode($json);
}

function makeTodo($userID, $title, $description = null, $dueDate = null)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("INSERT INTO todos (userID, title, description, dueDate) VALUES (:userID_value, :title_value, :description_value, :dueDate_value)");
    $stmt->bindValue(":userID_value", $userID, PDO::PARAM_INT);
    $stmt->bindValue(":title_value", $title, PDO::PARAM_STR);
    $stmt->bindValue(":description_value", $description, PDO::PARAM_STR);
    $stmt->bindValue(":dueDate_value", $dueDate, PDO::PARAM_STR);
    $stmt->execute();

    return json_encode("Success");
}

function changeCompleteStatusTodo($todoID, $complete)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("UPDATE todos SET complete = :complete_value WHERE todoID = :todoID_value");
    $stmt->bindValue(":complete_value", $complete, PDO::PARAM_INT);
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    $stmt->execute();

    return json_encode("Success");
}

function updateTodo($todoID, $title, $description, $dueDate)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("UPDATE todos SET title = :title_value, description = :description_value, dueDate = :dueDate_value WHERE todoID = :todoID_value");
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    $stmt->bindValue(":title_value", $title, PDO::PARAM_STR);
    $stmt->bindValue(":description_value", $description, PDO::PARAM_STR);
    $stmt->bindValue(":dueDate_value", $dueDate, PDO::PARAM_STR);

    $stmt->execute();

    return json_encode("Success");
}

function deleteTodo($todoID)
{
    $dbh = get_pdo();

    $stmt = $dbh->prepare("DELETE FROM todos WHERE todoID = :todoID_value");
    $stmt->bindValue(":todoID_value", $todoID, PDO::PARAM_INT);
    $stmt->execute();

    return;
}

// Allows us to read Axios request object as request data
$json = file_get_contents('php://input');

$data = json_decode($json, true);

switch ($data["action"]) {
    case "signup":
        if ($data["userEmail"] && $data["userPassword"]) {
            signupUser($data["userEmail"], $data["userPassword"]);
            die();
        }
        echo "Inavlid Params";
        die();
    case "login":
        if ($data["userEmail"] && $data["userPassword"]) {
            echo loginUser($data["userEmail"], $data["userPassword"]);
            die();
        }
        echo "Invalid Params";
        die();
    case "getTodos":
        if ($data["userID"]) {
            echo getTodos($data["userID"]);
            die();
        }
        echo json_encode("Invalid Params");
        die();
    case "makeTodo":
        echo makeTodo($data["userID"], $data["title"], isset($data["description"]) ? $data["description"] : null, isset($data["dueDate"]) ? $data["dueDate"] : null);
        die();
    case "changeCompleteStatusTodo":
        echo changeCompleteStatusTodo($data["todoID"], $data["complete"]);
        die();
    case "updateTodo":
        echo updateTodo($data["todoID"], $data["title"], $data["description"], $data["dueDate"]);
        die();
    case "deleteTodo":
        deleteTodo($data["todoID"]);
        die();
    default:
        die();
}
