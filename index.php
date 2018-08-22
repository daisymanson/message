<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>留言板</title>
</head>
</html>

<?php
require_once('class_db_maria.php');
$db = new DB;

$result = select_list($db,$arr_input);
//var_dump($result);

if(is_array($result) == true)
{
  foreach ($result as $key => $value1)
  {
    echo ($key + 1).'<br />';
    echo $value1['guest_id'].'<br />';
    echo $value1['content'].'<br />';
    echo $value1['date'].'<br />';
    echo '<hr />';
  }
}

unset($arr_input);
unset($sql_input);


function select_list($db, $arr_input)
{
    $result = $db->table('message')
                 ->select('guest_id, content, date')
                 ->where('is_enable = 0', $arr_input)
                 ->get();
    return $result;
}
?>
