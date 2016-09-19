<?php
if (session_status() == PHP_SESSION_NONE) {
    ini_set("session.cookie_httponly",1);
    session_start();
}
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

if (isset($_SESSION['verified']) and $_SESSION['verified'] and isset($_SESSION['username'])) {
    echo json_encode(array(
        "success"=>true,
        "username"=>$_SESSION['username'],//safer from XSS attack
        "message"=>"already logined"));
} else {
    require 'database.php';
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $stmt = $mysqli->prepare("select count(*), username, password, id from users where username=?");
    if(!$stmt){
        // printf("Query Prep Failed: %s\n", $mysqli->error);
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed: ".$mysqli->error
        )); //technically i think we should not present this error to user
        exit;
    }
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($cnt, $u, $p, $id);
    $stmt->fetch();
    if($cnt==0){
        $_SESSION['verified'] = false;

        echo json_encode(array(
            "success"=>$_SESSION['verified'],
            "message"=>"invalid u"
            ));
        usleep(5);
        // header("Location: index.php");
        exit;
    }
    if( $cnt == 1 && crypt($password, $p)==$p){
        
        $_SESSION['username'] = htmlentities($u);//safer from XSS attack
        $_SESSION['verified'] = true;
        $_SESSION['user_id'] = htmlentities($id);//safer from XSS attack
        echo json_encode(array(
            "success" => $_SESSION['verified'],
            "message" => "you've logined",
            "username" =>$_SESSION['username'] 
        ));
        $stmt->close();
        exit;
    }else{
        // Login failed; redirect back to the login screen
        $_SESSION['verified'] = false;
        echo json_encode(array(
            "success" => $_SESSION['verified'],
            "message" => "invalid p"
        ));
        $stmt->close();
        exit;   
    }
}
?>