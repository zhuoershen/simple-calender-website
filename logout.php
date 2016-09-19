<?php
if (session_status() == PHP_SESSION_NONE) {
    ini_set("session.cookie_httponly",1);
    session_start();
}
$_SESSION = array();
session_destroy();
error_log(json_encode($_SESSION));
// how will logout failed?
echo json_encode(array(
    "success" => true,
    "message" => "Bye, bithes!",
    "test" =>"test"
));
exit;
?>