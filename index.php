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

$result = select_list($db, $arr_input);

unset($arr_input);

?>
留言板
<table>
  <tr>
    <th>No</th>
    <th>ID</th>
    <th>Content</th>
    <th>Date</th>
    <th>update/delete</th>
  </tr>

  <?php
  if(is_array($result) == true)
  {
    foreach ($result as $key => $value1)
    {
      ?>
      <tr>
        <td><?php echo ($key + 1);?></td>
        <td><?php echo $value1['guest_id'];?></td>
        <td><?php echo $value1['content'];?></td>
        <td><?php echo $value1['date'];?></td>
        <td>
          <button onclick="update_list()">修改</button>
          <button onclick="delete_list()">刪除</button>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>

<?php
function select_list($db, $arr_input)
{
  $result = $db->table('message')
               ->select('guest_id, content, date')
               ->where('is_enable = 0', $arr_input)
               ->get();
  return $result;
}

unset($arr_input);
?>
<script>
function update_list()
{
  window.parent.location.href='update.php';
}

function delete_list()
{
  window.parent.location.href='delete.php';
}
</script>
