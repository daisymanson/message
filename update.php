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
  <input type="text" name="content">
  <input type="submit" value="Submit">
</form>
</html>

<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_input['id'] = $_GET['id'];

get_list($db, $arr_input);

$array_input['content'] = $_POST['content'];
$array_defult['id'] = $arr_input['id'];

print_r($array_input['content']);
$db->debug();

mod_list($db, $array_input, $array_defult);

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

// function redirect_js_href($msg,$url)
// {
//   if($url == NULL)
//   {
//     $url = 'index.php';
//   }
//
//   echo '<script>';
//   if($msg)
//   {
//     echo "alert('".$msg."');";
//   }
//   echo 'window.location.href="'.$url.'";';
//   echo '</script>';
// }

?>
