<?php
require 'init.php';

$security = true;
require "ssi_top.php";

$task = $get_post['task'];
$message = '';
$key = $get_post['id'];

if (!isset($key) || $key <= 0) {
    $message = "Account doesn't exist";
} else {
    $ppl = new people();
    $ppl->load($key, 'id');
}

if ($task == 'edit') {
    
    $name = trim($get_post['name']);
    $email = trim($get_post['email']);
    $password = trim($get_post['password']);
    $password_verify = trim($get_post['password_verify']);

    if (strlen($name) < 2) {
        $message = "Name must be at least 2 characters long";
    } elseif (empty($password)) {
        $message = "Password must be at least 5 characters long";
    } elseif (strlen($password) < 5) {
        $message = "Password must be at least 5 characters long";
    } elseif ($password != $password_verify) {
        $message = "Passwords do not match";
    } else {
        $ppl->values['password'] = hash('sha256', $password);
        $ppl->values['name'] = $name;
        $ppl->values['email'] = $email;

        $ppl->update();
        header("Location: page_listing.php?id=$key");
        exit;
    }
}

?>

<script language="javascript">
    function emailCheck() {
        var strength = document.getElementById('email_check');
        var email = document.getElementById("email");
        var bt = document.getElementById("submit_button");
        var enoughRegex = new RegExp("[a-z0-9]+@[a-z]+\.[a-z]{3,4}", "g");
        if (email.value.length == 0) {
            strength.innerHTML = '';
            bt.disabled = true;
        } else if (enoughRegex.test(email.value)) {
            strength.innerHTML = '<span style="color:green"> Good!</span>';
            bt.disabled = false;
        } else {
            strength.innerHTML = '<span style="color:red"> Please Type a Real Email</span>';
            bt.disabled = true;
        }
    }
</script>

<script language="javascript">
    function passwordChanged() {
        var strength = document.getElementById('strength_meter');
        var pwd = document.getElementById("password");
        var strengthText = document.getElementById("strength_text");
        var strongRegex = new RegExp("^(?=.{14,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        var mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        var enoughRegex = new RegExp("(?=.{5,}).*", "g");

        if (pwd.value.length == 0) {
            strength.value = 0;
            strengthText.innerText = 'Password Strength';
            strength.style.backgroundColor = '#ddd';
        } else if (false == enoughRegex.test(pwd.value)) {
            strength.value = 20;
            strengthText.innerText = 'Very Weak';
            strength.style.backgroundColor = 'red';
        } else if (strongRegex.test(pwd.value)) {
            strength.value = 100;
            strengthText.innerText = 'Strong';
            strength.style.backgroundColor = 'green';
        } else if (mediumRegex.test(pwd.value)) {
            strength.value = 60;
            strengthText.innerText = 'Medium';
            strength.style.backgroundColor = 'orange';
        } else {
            strength.value = 40;
            strengthText.innerText = 'Weak';
            strength.style.backgroundColor = 'yellow';
        }
    }
</script>

Please enter change your desired fields below:

<br>

<?php if ($message) { ?>
    <div style="color:red;"><?= $message ?></div><br>
<?php } ?>

<!-- All the form element names (except task) match the DB table names  -->

<form name="form1" action="page_update.php" method="POST">
    <input type="hidden" name="task" value="edit">
    <input type="hidden" name="id" value="<?= $key ?>">

    <br>
    Name:
    <input type="text" name="name" value="<?= $ppl->values['name'] ?>">
    <br>
    <br>
    Email:
    <input type="email" name="email" id="email" onkeyup="return emailCheck();" value="<?= $ppl->values['email'] ?>">
    <span id="email_check"></span>
    <br><br>
    Password:
    <input type="password" name="password" id="password" onkeyup="return passwordChanged();">
    <progress max="100" value="0" id="strength_meter"></progress>
    <span id="strength_text">Password Strength</span>
    <br><br>
    Verify Password: <input type="password" name="password_verify" value="">
    <br>
    <input type="hidden" name="time" value="<?= $ppl->values['time'] ?>">
    <input type="hidden" name="ip" value="<?= $ppl->values['ip'] ?>">
    <input type="hidden" name="api_key" value="<?= $ppl->values['api_key'] ?>">
    <br>
    <br>
    <button type="submit" id="submit_button">Submit</button>
    <button type="button" onclick="window.location.href='page_listing.php?id=<?= $key ?>'">Back</button>
</form>

<?php

require 'ssi_bottom.php';
?>
