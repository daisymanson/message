<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>留言板</title>
  <style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
  </style>
</head>
</html>

<?php
require_once('class_db_maria.php');
$db = new DB;

$result = select_list($db,$arr_input);

echo "留言板";
echo "<table>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>ID</th>";
echo "<th>Content</th>";
echo "<th>Date</th>";
echo "<th colspan='2'>btn</th>";
echo "</tr>";

if(is_array($result) == true)
{
  foreach ($result as $key => $value1)
  {
    echo "<tr>";
    echo "<td>".($key + 1)."</td>";
    echo "<td>".$value1['guest_id']."</td> ";
    echo "<td>".$value1['content']."</td> ";
    echo "<td>".$value1['date']."</td> ";
    echo "<td>"."update"."</td>";
    echo "<td>"."delete"."</td>";
    echo "</tr>";
    echo "</table>";
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
