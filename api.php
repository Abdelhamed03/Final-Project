<?php
require 'init.php';
$security=true;
require 'security.php';

header('Access-Control-Allow-Origin: *');                 
header('Content-Type: application/json; charset=UTF-8');  

$sql = "SELECT * FROM " . 'shakespeare_works' . " ORDER BY work_title";
$result = lib::db_query($sql);



$arr = [];

// Token validation
$token = $_GET['token'] ?? '';
$ppl = new people();
$ppl->load(trim($token), 'api_key');

if (!$ppl->get_id_value()) {
    echo json_encode(['error' => 'Invalid API Key']);
    exit;
}

$api = [];
$arr = [];

if ( isset($_GET['act']) && strlen(trim($_GET['act'])) > 0 && isset($_GET['scene']) && strlen(trim($_GET['scene'])) > 0 && isset($_GET['work']) && strlen(trim($_GET['work'])) > 0) {
    $sql = "SELECT * FROM ". 'shakespeare_paragraphs' .
      " JOIN ". 'shakespeare_chapters' . " ON par_work_id=chap_work_id AND chap_act=par_act AND chap_scene=par_scene
      WHERE par_work_id = '". $_GET['work'] ."' AND par_act = '". $_GET['act'] ."' AND par_scene = '". $_GET['scene'] ."';";
    $result = lib::db_query($sql);

    $query_string = "?token=" . trim($get_post['token']) . "&work=" . trim($_GET['work']) . "&act=" . trim($_GET['act']) . "&scene=" . trim($_GET['scene']);

    while ($row = $result->fetch_assoc()) {
        $api["chap_description"] = $row['chap_description'];
        $arr = [];
        $arr[] = $row['par_number'];
        $arr[] = $row['par_char_id'];
        $arr[] = $row['par_text'];
        $api['paragraphs'][] = $arr;
    }

} elseif ( isset($_GET['work']) && strlen(trim($_GET['work'])) > 0 ) {
    $sql = "SELECT * FROM ". 'shakespeare_chapters' . " WHERE chap_work_id = '". $_GET['work'] ."';";
    $result = lib::db_query($sql);
    
    $query_string = "?token=" . trim($get_post['token']) . "&work=" . trim($_GET['work']);

    while ($row = $result->fetch_assoc()) {
        $arr["chap_id"] = $row['chap_id'];
        $arr["chap_work_id"] = $row['chap_work_id'];
        $arr["chap_act"] = $row['chap_act'];
        $arr["chap_chap"] = $row['chap_chap'];
        $arr["chap_description"] = $row['chap_description'];
        array_push($api, $arr);
    }
}else{
    $query_string = "?token=" . $get_post['token'];
    
      while ( $row = $result->fetch_assoc() ) {
        $arr['work_id'] = $row['work_id'];
        $arr['work_title'] = $row['work_title'];
        $arr['work_long_title'] = $row['work_long_title'];
        $arr['work_year'] = $row['work_year'];
        $arr['work_genre'] = $row['work_genre'];
        array_push($api, $arr);
      }
    }
$api_log = new api();
$api_log->values['api_log_users_id'] = $ppl->get_id_value();
$api_log->values['api_log_time'] = time();
$api_log->values['api_log_ip'] = $_SERVER['REMOTE_ADDR'];
$api_log->values['api_log_ip_query'] = $_SERVER['QUERY_STRING'];
$api_log->save();

$json_string = empty($api) ? "null" : json_encode($api);
echo $json_string;
exit;
?>
