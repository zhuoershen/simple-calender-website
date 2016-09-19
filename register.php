<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set("session.cookie_httponly",1);
    session_start();
}
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
// Check to see if the username and password are valid
require "database.php";
$username = $_POST["username"];
$stmt = $mysqli->prepare("select count(*) from users where username=?");
if(!$stmt){
    printf("Query Prep Failed when check for dup: %s", $mysqli->error);
    $mysqli->close();
    exit;
}
$stmt->bind_param('s',$username);
$result = $stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
if ($result!=0) {//check whether username have been taken
	   //echo "username ".$username." already been take!";
    echo json_encode(array(
        "success" => false,
        "message" => "Username have already been taken"
    ));
    exit;
} else {
    $stmt->close();
	$password = htmlspecialchars($_POST["password"]);
	$cost = 10;
    $salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 22);
	$salt = sprintf("$2a$%02d$", $cost) . $salt;
	$hash = crypt($password, $salt);//this is the password
    $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed when inserting: %s", $mysqli->error);
        $stmt->close();
        $mysqli->close();
        exit;
    }
    $stmt->bind_param('ss',$username,$hash);
    $stmt->execute();
	if(!$stmt){
        printf("Insertion Failed: %s", $mysqli->error);
        $stmt->close();
        $mysqli->close();
        echo json_encode(array(
            "success" => false,
            "message" => "Insertion failed: ".$mysqli->error
        ));
        exit;
    } else {
        
    	$_SESSION['verified'] = true;
    	$_SESSION['username'] = htmlentities($username);
        $stmt = $mysqli->prepare("select id from users where username=?");
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $_SESSION['user_id'] = htmlentities($id);
        $stmt->close();
        $mysqli->close();
        // $flag2=true;
        echo json_encode(array(
            "success" => true,
            "username" => $username
        ));
        exit;
    }
}
?>