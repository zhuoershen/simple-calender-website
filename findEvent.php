<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set("session.cookie_httponly",1);
    session_start();
}
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
// Check to see if the username and password are valid
if (!isset($_SESSION['username'])) {
    echo json_encode(array(
        "success"=>false,
        "message"=>"need to login from findEvent.php ".json_encode($_SESSION),
        "session"=>$_SESSION));
    exit;
}

require "database.php";
$month = htmlentities((int)$_POST['month']+1);
$year = htmlentities((int)$_POST['year']);
//$month = (int)3;
//$year = (int)2016;
$user_id = htmlspecialchars($_SESSION['user_id']);
// echo "id ".$user_id;
$events = array();

$stmt = $mysqli->prepare("select event_name, event_time, detail, id from events where user_id=? and MONTH(event_time)=? and YEAR(event_time)=?");
if(!$stmt){
    echo json_encode(array('messahe' => "Query Prep Failed ".$mysqli->error,
        'success'=>false));
    printf("Query Prep Failed when check for dup: %s", $mysqli->error);
    $mysqli->close();
    exit;
}
$stmt->bind_param('sss', $user_id, $month, $year);
$stmt->execute();
$stmt->bind_result($event_name, $event_time, $detail, $id);

while($stmt->fetch()){
	array_push($events, array(
        'id' => $id,
        'event_name' => $event_name,
        'event_time' =>$event_time,
        'datail' => $detail
    ));
	
}
$stmt->close();
//if ($num!=0) {
    echo json_encode(array(
        "success" => true,
        "events" => $events
    ));
    exit;
//}else{
//    echo json_encode(array(
//        "success" => false
//    ));
//    exit;
//}
?>