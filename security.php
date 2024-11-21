<?php
if (!isset($_SESSION['logon_cookie']) ) {
    header('Location: login_form.php?');
}

if (isset($_SESSION['logon_cookie'])) {
    $cookie_userID = json_decode($_SESSION['logon_cookie'], true);
    if ($cookie_userID && is_array($cookie_userID) && count($cookie_userID) > 0) {
        $ppl = new people();
        $ppl->load($cookie_userID[0], 'id');
        
        if (isset($_GET['id'])) {
            $logged_in_user_id = $ppl->get_id_value();
            $requested_user_id = $_GET['id'];

            if ($logged_in_user_id !== $requested_user_id) {
                $_SESSION['message'] = "You don't have access to that user's page.";
                header('Location: page_logon.php');
                exit;
            }
        }
    }
}
?>
