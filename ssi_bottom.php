

</div> <!-- End of Page Content -->

<?php
require 'init.php';
if ($security) {
  require "security.php";
}


$email = "Noone";
$name = "Guest";
$authenticated = false;


if (isset($_SESSION['logon_cookie'])) {
  $cookie_userID = json_decode($_SESSION['logon_cookie'], true);
  if ($cookie_userID && is_array($cookie_userID) && count($cookie_userID) > 0) {
    $ppl = new people();
    $ppl->load($cookie_userID[0], 'id');
    if (isset($ppl->values['email'])) {
      $email = $ppl->values['email'];
      $name = $ppl->values['name'];
      $authenticated = true;
    }
  }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ORM/AR Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
</head>
<body>
    <div id="page_wrapper">
        <div id="header">
            <?php
            

            if ($email !== "Noone") {
              echo "<h3>Hello " . htmlspecialchars($name) . "</h3>";  
            } elseif (isset($currentPage) && $currentPage == 'login') {
              echo "<h3>Identify Yourself!</h3>";
            } elseif (isset($currentPage) && $currentPage == 'signup') {
              echo "<h3>Im Judging Your Password :)</h3>";
            } else {
              echo "<h3>Hello</h3>";
            }
            ?>
        </div>
</html>