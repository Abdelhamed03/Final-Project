<?php
require 'init.php';
$currentPage = 'login';
$security = false; 
require "ssi_top.php"; 


$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); 

$rememberedEmail = isset($_COOKIE['rememberEmail']) ? $_COOKIE['rememberEmail'] : '';


if (isset($_SESSION['logon_cookie'])) {
    $cookie_userID = json_decode($_SESSION['logon_cookie'], true);
    if ($cookie_userID[0] > 0) {
        header("Location: page_listing.php?id=" . $cookie_userID[0]);
        exit;
    }
}

$task = $get_post['task'] ?? '';
if ($task === 'logon') {
    $ppl = new people();
    $logon = new logon();

    $ppl->load(trim($get_post['logon_email']), 'email');

    if (empty($ppl->values['email'])) {
        $message = "User does not exist";
    } elseif ($ppl->values['password'] !== hash('sha256', $get_post['logon_password'])) {
        $message = "Incorrect Password";
    } else {
        
        $logon_token = hash('md5', $ppl->values['email'] . time());
        $logon->set_id_value('"' . $logon_token . '"');
        $logon->values['logon_users_id'] = $ppl->get_id_value();
        $logon->values['logon_time'] = time();
        $logon->values['logon_last'] = time();
        $logon->values['logon_ip'] = $_SERVER['REMOTE_ADDR'];

        $logon->save();

        $cookie_expires = time() + 60 * 20;
        $cookie = [$ppl->get_id_value(), $logon_token];
        setcookie("logon_cookie", json_encode($cookie), $cookie_expires);
        
        $_SESSION['logon_cookie'] = json_encode($cookie);

        if (isset($get_post['logon_remember']) && $get_post['logon_remember'] === 'yes') {
            setcookie('rememberEmail', $ppl->values['email'], time() + 60*60*24*30); // 30 days
        }

        header("Location: page_listing.php?id=" . $ppl->get_id_value());
        exit;
    }
}
?>

<a href="page_form.php">Create an Account</a> <br><br>

<?php if ($message) { ?>
    <div style="color:red;"><?= htmlspecialchars($message) ?></div><br>
<?php } ?>

<form name="page_logon" action="page_logon.php" method="POST">
    <input type="hidden" name="task" value="logon">

    Email:
    <input type="text" name="logon_email" value="<?= htmlspecialchars($rememberedEmail) ?>"><br><br>

    Password:
    <input type="password" name="logon_password"><br><br>

    <input type="checkbox" name="logon_remember" value="yes" <?= isset($_COOKIE['rememberEmail']) ? 'checked' : '' ?>> Remember email? <br><br>

    <button type="submit">Submit</button>
</form>

<?php require 'ssi_bottom.php'; ?>
