<?php
if (session_status() == PHP_SESSION_NONE) {
    ini_set("session.cookie_httponly",1);
    session_start();
}
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

if (isset($_SESSION['verified']) and $_SESSION['verified'] and isset($_SESSION['username'])) {
    echo json_encode(array(
                    "success"=>true,
                    "username"=>$_SESSION['username'],
                    "message"=>"already logined"));
} else {
    echo json_encode(array(
            "success"=>false,
            "username"=>null,
            "message"=>"already logined"));
}
?>