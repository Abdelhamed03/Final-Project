<?
require 'init.php'; // database connection, etc




$security = true;

require "ssi_top.php";

$key = $get_post['id'];
//var_dump($key);

$ppl = new people();

$ppl->load($key, 'id');


?>
<br><br>
  <b>Account Information:</b>
  <br>
  <br>

  <table width="" border="3" cellspacing="0" cellpadding="5">
  <tr  valign="top">
      <td>Name</td>
      <td>Email</td>
      <td>Time</td>
      <td>IP Address</td>
      <td>API Token</td>
   </tr>
   <tr  valign="top">
      <td><?= $ppl->values['name'] ?></td>
      <td><?= $ppl->values['email'] ?></td>
      <td><?= $ppl->values['time'] ?></td>
      <td><?= $ppl->values['ip'] ?></td>
      <td><?= $ppl->values['api_key'] ?></td>
   </tr>
   </table>
   <br>
   <br>
   <a href="page_update.php?id=<?=$key?>">Edit Profile </a>
   <br>
   <br>
   <br>
   <a href="page_API.php?id=<?=$key?>">API Summary</a>
   <br>
   <br>
   <br>
   <a href="api.php?token=<?=$ppl->values['api_key']?>">Call API</a>
   <br>
   <br>
   <br>




<?

require 'ssi_bottom.php';
?>