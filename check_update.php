<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_input['content'] = $_POST['content'];
$arr_input['id'] = $_POST['id'];

if(is_array($arr_input) == ture)
{
  $array_input['content'] = $arr_input['content'];
  $array_defult['id'] = $arr_input['id'];
  mod_list($db, $array_input, $array_defult);
  redirect_js_href('success', 'index.php');
}

unset($arr_input);
unset($array_input);
unset($array_defult);

function mod_list($db,$array_input, $array_defult)
{
	$res = $db->table('message')
					  ->update($array_input)
					  ->where('id = ? ', $array_defult)
					  ->set();
	return $res;
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
