<?php
require_once('class_db_maria.php');
$db = new DB;

$arr_input['id'] = $_GET['id'];
$arr_input['content'] = $_POST['content'];

$sql_input['id'] = $arr_input['id'];
$res = get_list($db, $sql_input);
unset($sql_input);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>留言板</title>
</head>
<?php
if(is_array($res) == ture)
{
  ?>
  <form action="check_update.php" method="POST">
    <br>
    Content
    <input type="text" name="content" value="<?php echo $res[0]['content'];?>"></input>
    <input type="hidden" name="id" value="<?php echo $res[0]['id'];?>"></input>
    <input type="submit" value="Submit"></input>
    <input type ="button" onclick="history.back()" value="Cancel"></input>
<?php
}
else
{
  echo 'NoData';
}
?>
</form>
</html>

<?php
function get_list($db, $arr_input)
{
	$result = $db->table("message")
				       ->select('*')
               ->where('is_enable = 0 AND id = ?', $arr_input)
				       ->get();
	return $result;
}
?>
