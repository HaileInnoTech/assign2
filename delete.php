<?php
require_once('db_config.php');
if (isset($_POST['submit'])) {
  var_dump($_POST);
  $conn = mysqli_connect($host, $user, $pswd, $dbnm);
  $user = $_POST['user_id'];
  $friend_id = $_POST['friend_id'];
  $sql_delete = sprintf("DELETE FROM myfriends WHERE friend_id1 = '%s' AND friend_id2 = '%s'", $conn->real_escape_string($user), $conn->real_escape_string($friend_id));
  $conn->query($sql_delete);
  $sql_delete = sprintf("DELETE FROM myfriends WHERE friend_id2 = '%s' AND friend_id1 = '%s'", $conn->real_escape_string($user), $conn->real_escape_string($friend_id));
  $conn->query($sql_delete);
  $conn->close();
  header('Location: friendlist.php');
}
?>