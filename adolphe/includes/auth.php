<?php
// includes/auth.php - session security & auth helpers
if(session_status()===PHP_SESSION_NONE){
    ini_set('session.cookie_httponly',1);
    ini_set('session.use_strict_mode',1);
    session_start();
}
function auth_require(){
    if(empty($_SESSION['admin_id'])){
        header('Location: login.php'); exit;
    }
    // timeout 30 min
    if(isset($_SESSION['last_activity']) && time()-$_SESSION['last_activity']>1800){
        session_unset(); session_destroy();
        header('Location: login.php?timeout=1'); exit;
    }
    $_SESSION['last_activity']=time();
}
function auth_login(int $id, string $email, string $username){
    session_regenerate_id(true);
    $_SESSION['admin_id']=$id;
    $_SESSION['admin_email']=$email;
    $_SESSION['admin_username']=$username;
    $_SESSION['last_activity']=time();
}
