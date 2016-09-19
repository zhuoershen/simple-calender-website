<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set("session.cookie_httponly",1);
    session_start();
}
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
// Check to see if the username and password are valid
    require "database.php";
 	$name = $_POST["name"];
    $time = $_POST["time"];//the time that user input
    $detail = $_POST["detail"];
    $username = $_POST["username"];
    $date = $_POST["timestamp"];//the date 
    
    //make a timestamp
   
    
   
    $timestamp = date('Y-m-d H:i:s', strtotime("$date $time")); 
     
	/*get user id*/
	$stmt = $mysqli->prepare("select id from users where username=?");
    if(!$stmt){
        printf("Query Prep Failed when check for dup: %s", $mysqli->error);
        $mysqli->close();
        exit;
    }
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
	
	/*insert events*/
    $stmt = $mysqli->prepare("insert into events (user_id, event_name, event_time, detail) values (?,?,?,?)");
    if(!$stmt){
        printf("Query Prep Failed when check for dup: %s", $mysqli->error);
        $mysqli->close();
        exit;
    }
    $stmt->bind_param('ssss',$user_id,$name,$timestamp,$detail);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "name" => $username,
        "date"=>$date
    ));
    exit;
    
?>