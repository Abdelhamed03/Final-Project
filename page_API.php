<?php
require 'init.php';
require 'class_pageable_list.php';

$security = true;
require_once "ssi_top.php";
require "security.php";

$user_id = $_GET['id'] ?? 0; 

$query = "SELECT * FROM " . API_TABLE . " WHERE api_log_users_id = $user_id";

$result = mysqli_query($mysqli, $query);
$count_calls = mysqli_num_rows($result);

$work_hits = 0;
$act_scene_hits = 0;
$paragraph_hits = 0;
$latest_time = 0;

while($row = $result->fetch_assoc()){
    if ($row['api_log_users_id'] == $user_id){ 
        $work_hits++;

        if (strpos($row['api_log_ip_query'], 'work') !== false){
            $act_scene_hits++;
        }

        if (strpos($row['api_log_ip_query'], 'act') !== false){
            $paragraph_hits++;
        }

        $latest_time = max($latest_time, $row['api_log_time']);
        
    }
}
$latest_hit_display = $latest_time > 0 ? date("Y/m/d h:i:s", $latest_time) : "N/A";

$listing = new pg_list($query, 'api_log_id', 'api_log_time', 'DESC', 'even_row_css', 'odd_row_css', 1, 5, true, 4, 'even_row_css', 'odd_row_css', 'highlight_css');


$listing->add_column('api_log_id', 'Log ID');
$listing->add_column('api_log_users_id', 'User ID');
$listing->add_column('api_log_time', 'Log Time');
$listing->add_column('api_log_ip', 'Log IP');
$listing->add_column('api_log_ip_query', 'Log Query');

$listing->init_list();

$page_title = "Pageable Listing";
?>


<style>

.even_row_css {
   background-color:#EEE;
   font-size:10pt;
}
.odd_row_css {
   background-color:#DDD;
   font-size:10pt;
}
.highlight_css {
   background-color:#DDF;
   font-size:10pt;
}
tbody th {
  text-align: left;
}
</style>

API Summary: 
<br><br>
<a href="page_listing.php?id=<?= $user_id ?>">Go Back</a>
<br><br>

<?= $listing->get_html() ?>

<script type="text/javascript">

  function confirm_delete(=id, =name) {
    var choice = confirm("Are you sure you want to delete " + =name + "?");

    if ( choice == true ) {
      window.location.href = "page_form.php?task=delete&=id="+=id;
    }
  }

</script>
<br>
Summary:
<br>

<table width="" border="2" cellspacing="0" cellpadding="5">
  <tr valign="top">
    <td>Total Calls</td>
    <td>Work Calls</td>
    <td>Act or Scene Calls</td>
    <td>Paragraph Calls</td>
    <td>Latest Call</td>
  </tr>
  <tr valign="top">
    <td><?= $count_calls ?></td>
    <td><?= $work_hits ?></td>
    <td><?= $act_scene_hits ?></td>
    <td><?= $paragraph_hits ?></td>
    <td><?= $latest_hit_display ?></td>
  </tr>
</table>

<br><br>

<?php require_once "ssi_bottom.php"; ?>