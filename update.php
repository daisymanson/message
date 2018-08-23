<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>留言板</title>
</head>
<form action="" method="POST">
  <?php echo $_GET['id']; ?>
  <br>
  Content
  <input type="text" name="content"></input>
  <input type="submit" value="Submit"></input>
  <input type ="button" onclick="history.back()" value="Cancel"></input>
</form>
</html>

<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_input['id'] = $_GET['id'];

get_list($db, $arr_input);

$array_input['content'] = $_POST['content'];
$array_defult['id'] = $arr_input['id'];

mod_list($db, $array_input, $array_defult);

unset($arr_input);
unset($array_defult);
unset($array_input);

function get_list($db, $arr_input)
{
	$result = $db->table("message")
				       ->select('*')
               ->where('is_enable = 0 AND id = ?', $arr_input)
				       ->get();
	return $result;
}

function mod_list($db,$array_input, $array_defult)
{
	$res = $db->table('message')
					  ->update($array_input)
					  ->where('id = ? ', $array_defult)
					  ->get();
	return $res;
}
?>
