<?php
require_once('db_config.php');
if (isset($_POST['submit'])) {
  $conn = mysqli_connect($host, $user, $pswd, $dbnm);
  $user = $_POST['user_id'];
  $friend_id = $_POST['friend_id'];
  $sql_insert = sprintf("INSERT INTO myfriends (friend_id1, friend_id2) VALUES ('%s', '%s')", $conn->real_escape_string($user), $conn->real_escape_string($friend_id));
  $conn->query($sql_insert);
  $sql_insert = sprintf("INSERT INTO myfriends (friend_id2, friend_id1) VALUES ('%s', '%s')", $conn->real_escape_string($user), $conn->real_escape_string($friend_id));
  $conn->query($sql_insert);
  $conn->close();
  header('Location: friendadd.php');

}
?>