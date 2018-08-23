<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_def['id'] = $_GET['id'];
$sql_def['id'] = $arr_def['id'];

$result = checke_data($db, $sql_def);
$arr_inpt['is_enable'] = 1;

if(is_array($result) == ture)
{
  $db->debug();
  delete_list($db, $arr_inpt, $sql_def);
  unset($sql_def);
  redirect_js_href('success', 'index.php');
}
else
{
  die("Failed");
}

unset($arr_inpt);

function delete_list($db, $arr_inpt, $arr_def)
{
	$res = $db->table('message')
					  ->update($arr_inpt)
					  ->where('id = ? ', $arr_def)
					  ->set();
	return $res;
}

function checke_data($db, $arr_def)
{
  $result = $db->table("message")
               ->select('*')
               ->where('id = ?', $arr_def)
               ->get();
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
