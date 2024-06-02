<?php
session_start();
require_once('data_process.php');
// Check if the curentUser is logged in
if (!isset($_SESSION['loggedin'])) {
  header('Location: login.php');
  exit;
}

$friend_list = array();

// Get the curentUser's name from the session
$name = $_SESSION['name'];
require_once('db_config.php');

$conn = mysqli_connect($host, $user, $pswd, $dbnm);
$sql = sprintf("SELECT * FROM friends WHERE profile_name ='%s'", $conn->real_escape_string($name));
$result = executeSQLStatement($host, $user, $pswd, $dbnm, $sql);
$curentUser = $result->fetch_assoc();

//pagination
// Count total number of records
$sql_count = sprintf("
    SELECT COUNT(*) AS total_records
    FROM friends AS f
    LEFT JOIN myfriends AS mf ON f.friend_id = mf.friend_id2 AND mf.friend_id1 = '%s'
    WHERE mf.friend_id2 IS NULL AND f.friend_id != '%s'",
  $conn->real_escape_string($curentUser['friend_id']),
  $conn->real_escape_string($curentUser['friend_id'])
);
$result_count = executeSQLStatement($host, $user, $pswd, $dbnm, $sql_count);
$row_count = $result_count->fetch_assoc();
$total_records = $row_count['total_records'];

// Calculate total number of pages
$rows_per_page = 5;
$total_pages = ceil($total_records / $rows_per_page);

// Get current page number
if (isset($_GET["page"])) {
  $page = $_GET["page"];
  if ($page < 1 || $page > $total_pages) {
    $page = 1;
  }
} else {
  $page = 1;
}

// Calculate starting record for current page
$start_from = ($page - 1) * $rows_per_page;

// Retrieve records for current page
$sql_friend = sprintf("
    SELECT f.*
    FROM friends AS f
    LEFT JOIN myfriends AS mf ON f.friend_id = mf.friend_id2 AND mf.friend_id1 = '%s'
    WHERE mf.friend_id2 IS NULL AND f.friend_id != '%s'
    LIMIT %d,%d",
  $conn->real_escape_string($curentUser['friend_id']),
  $conn->real_escape_string($curentUser['friend_id']),
  $start_from,
  $rows_per_page
);
$result_friends = executeSQLStatement($host, $user, $pswd, $dbnm, $sql_friend);

//count friend
$sql_friend_no = sprintf("SELECT f.* from friends f inner join myfriends s 
on s.friend_id2= f.friend_id WHERE s.friend_id1 ='%s'", $conn->real_escape_string($curentUser['friend_id']));
$result2 = executeSQLStatement($host, $user, $pswd, $dbnm, $sql_friend_no);
$num_rows = mysqli_num_rows($result2);
?>

<!DOCTYPE html>
<html>
<div class="container bg-light">
  <?php
  include("nav.php") ?>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>FriendAdd</title>
  </head>

  <body>
    <br>
    <h2>
      <?php echo preprocess($name) ?>'s Friend Add Page
    </h2>
    <span class="d-flex justify-content-end">Total of friends is
      <?= preprocess($num_rows) ?>
    </span class="d-flex justify-content-end">
    <?php
    echo '<table class="table table-striped table-light">';
    echo '<tr>
          <th scope="col">Profile Name</th>
          <th scope="col">Mutual Friend</th>
          <th scope="col">Action</th></tr>';

    while ($friend_list = $result_friends->fetch_assoc()) {
      echo '<tr>';
      echo '<td>' . $friend_list['profile_name'] . '</td>';
      echo '<td>';

      $sql_mutual_friend = sprintf("SELECT f1.*
      FROM friends f1
      INNER JOIN myfriends mf1 ON f1.friend_id = mf1.friend_id2
      INNER JOIN myfriends mf2 ON mf1.friend_id1 = mf2.friend_id2
      INNER JOIN friends f2 ON mf2.friend_id1 = f2.friend_id
      WHERE f1.friend_id != f2.friend_id AND f1.friend_id = '%s' AND f2.friend_id = '%s'",
        $conn->real_escape_string($curentUser['friend_id']),
        $conn->real_escape_string($friend_list['friend_id'])
      );
      require_once('db_config.php');
      $result3 = executeSQLStatement($host, $user, $pswd, $dbnm, $sql_mutual_friend);
      $num_mutual_friend = mysqli_num_rows($result3);
      echo "$num_mutual_friend mutual friends";
      '</td>';
      echo '<td>
            <form method="post" action="add.php">
            <input type="hidden" name="user_id" value="' . $curentUser['friend_id'] . '" >           
            <input type="hidden" name="friend_id" value="' . $friend_list['friend_id'] . '" >           
            <button type="submit" name="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
            <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
          </svg>
            </button>
            </form> 
        </td>';
      echo '</tr>';
    }
    echo '</table>';
    echo '
          <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">';

    if ($page > 1) {
      echo "<li class='page-item'><a class='page-link' href='friendadd.php?page=" . max(1, $page - 1) . "'>Prev</a></li>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
      echo "<li class='page-item'><a class='page-link' href='friendadd.php?page=" . $i . "'";

      echo ">" . $i . "</a></li>";
    }
    if ($total_pages > $page) {
      echo "<li class='page-item'><a class='page-link' href='friendadd.php?page=" . min($total_pages, $page + 1) . "'>Next</a></li>";
    }
    echo '
    </ul>
    </nav>
    ';
    ?>
</div>
</body>

</html>