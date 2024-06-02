<?php
//local database config
$host = 'localhost';
$user = 'root';
$pswd = '';
$dbnm = 's103542974_db';

//swinbune databse config
// $host = 'feenix-mariadb.swin.edu.au';
// $user = 's103542974';
// $pswd = '280202'; 
// $dbnm = 's103542974_db'; 

$conn = createDBConnection($host, $user, $pswd, '');

function createDBConnection($host, $user, $pswd, $dbnm)
{
  $conn = mysqli_connect($host, $user, $pswd, $dbnm);
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }
  return $conn;
}

function executeSQLStatement($host, $user, $pswd, $dbnm, $sql)
{
  $conn = mysqli_connect($host, $user, $pswd, $dbnm);
  $result = $conn->query($sql);
  if (!$result) {
    die("SQL error: " . $conn->error);
  }
  $conn->close();
  return $result;
}

function checkEmailDuplicate($host, $user, $pswd, $dbnm, $sql, &$params)
{
  $conn = mysqli_connect($host, $user, $pswd, $dbnm);
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $stmt = $conn->stmt_init();
  if (!$stmt->prepare($sql)) {
    die("SQL error: " . $conn->error);
  }
  $types = array_shift($params);
  $bindParams = array_merge([$types], $params);

  $refParams = [];
  foreach ($bindParams as $key => $value) {
    $refParams[$key] = &$bindParams[$key];
  }
  call_user_func_array([$stmt, 'bind_param'], $refParams);

  //use try catch to check duplicate email
  try {
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
      return true; // Email already exists
    }
    die("SQL error: " . $e->getMessage());
  }
  $stmt->close();
  mysqli_close($conn);
  return false; // No email duplication
}
?>