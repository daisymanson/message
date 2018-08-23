<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_input['guest_id'] = $_POST['guest_id'];
$arr_input['content'] = $_POST['content'];

$sql_input['guest_id'] = $arr_input['guest_id'];
$sql_input['content'] = $arr_input['content'];
$sql_input['is_enable'] = 0;

$res = add_list($db, $sql_input);

if($res > 0)
{
  redirect_js_href('success', 'index.php');
}
else
{
  die("Failed");
}

function add_list($db,$arr_input)
{
  $result = $db->table('message')
               ->insert($arr_input)
               ->set();
  return $result;
}

function redirect_js_href($msg,$url)
{
  if($url == NULL)
  {
    $url = 'index.php';
  }

  echo '<script>';
  if($msg)
  {
    echo "alert('".$msg."');";
  }
  echo 'window.location.href="'.$url.'";';
  echo '</script>';
}
?>
