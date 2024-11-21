<?
require 'init.php'; 

$security = false;
$currentPage = 'signup';
require "ssi_top.php";


$task = $get_post['task'];
switch ($task) {
    case 'save':
        

        $ppl = new people();

       

        $ppl->load_from_form_submit();

        
        if ( strlen(trim($ppl->values['name'])) < 2 ) {
          $message = "Must provide a valid name (longer than 2 characters)";
          break; // fall back to form without saving
        }

        $person_2 = new people();
        $person_2->load(trim($ppl->values['email']), 'email');

        if ( trim($person_2->values['email']) ) {
          $message = "This email is already in use";
          break; // fall back to form without saving
        }

        if ( strlen(trim($ppl->values['password'])) < 5 ) {
         $message = "Password must be at least 5 characters";
         break; // fall back to form without saving
        }

        if ( trim($ppl->values['password']) != trim($get_post['password_verify']) ) {
         $message = "Password and verified password must match";
         break; // fall back to form without saving
        }

        

        $t=time();
        $ppl->values['time'] = (date("Y/m/d h:i:s", $t));

        $ppl->values['ip'] = $_SERVER['REMOTE_ADDR'];

        $ppl->values['api_key'] = hash('md5', $values['email'] . $t);


        $ppl->values['password'] = hash('sha256', trim($get_post['password']));
        
        $ppl->save();

        $id = $ppl->get_id_value();
        
        //session variable
        $cookie = [];
        $cookie[0] = $id;
        $_SESSION['logon_cookie'] = json_encode($cookie);
        
        header ("Location: page_listing.php?id=$id");
        exit;
        exit;
        break;

    ///////////////////////////////////////////////////////////////////
    default:
      
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



<a href="kill_cookie.php">Back To login</a>
<br><br>

Create an account below:

<? if ($message) { ?>
  <div style="color:red;"><?=$message?></div><br>
<? } ?>

<form name="form1" action="page_form.php" method="POST">
   <input type="hidden" name="task" value="save">
   <input type="hidden" name="id" value="<?= $id ?>">

   Name: <input type="text" name="name" value="<?= $ppl->values['name'] ?>">
   <br><br>
   Email:
   <input type="email" name="email" id="email" onkeyup="return emailCheck();" value="<?= $ppl->values['email'] ?>">
   <span id="email_check"></span>
   <br><br>
   Password:
   <input type="password" name="password" id="password" onkeyup="return passwordChanged();" value="<?= $ppl->values['password'] ?>">
   <progress max="100" value="0" id="strength_meter"></progress>
   <span id="strength_text">Password Strength</span>
   <br><br>
   Verify Password: <input type="password" name="password_verify" value="">
   <br>
   <input type="hidden" name="time" value="<?= $ppl->values['time'] ?>">
   <input type="hidden" name="ip" value="<?= $ppl->values['ip'] ?>">
   <br>
   <button type="submit"> Submit </button>
</form>

<? require 'ssi_bottom.php'; ?>
