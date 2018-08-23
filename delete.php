<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_def['id'] = $_GET['id'];

$arr_inpt['is_enable'] = 1;

$result = checke_data($db, $arr_def);

if(is_array($result) == ture)
{
  delete_list($db, $arr_inpt, $arr_def);
}
else
{
  var_dump($result);
}

unset($arr_def);
unset($arr_inpt);
unset($result);

function delete_list($db, $arr_inpt, $arr_def)
{
	$res = $db->table('message')
					  ->update($arr_inpt)
					  ->where('id = ? ', $arr_def)
					  ->get();
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
?>
