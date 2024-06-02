<?php
session_start();
require_once('data_process.php');
// Check if the curentUser is logged in
if (!isset($_SESSION['loggedin'])) {
  header('Location: login.php');
  exit;
}
// Get the curentUser's name from the session
$name = $_SESSION['name'];
require_once('db_config.php');
$conn = createDBConnection($host, $user, $pswd, $dbnm);
$sql = sprintf("SELECT * FROM friends WHERE profile_name ='%s'", $conn->real_escape_string($name));
$result = executeSQLStatement($host, $user, $pswd, $dbnm, $sql);
$curentUser = $result->fetch_assoc();

//count friend
$sql_friend = sprintf(
  "SELECT f.profile_name, f.friend_id from friends f inner join myfriends s on s.friend_id2= f.friend_id 
  WHERE s.friend_id1 ='%s'",
  $conn->real_escape_string($curentUser['friend_id'])
);
$result1 = executeSQLStatement($host, $user, $pswd, $dbnm, $sql_friend);
$num_rows = mysqli_num_rows($result1);


//pagenation
$total_records = mysqli_num_rows($result1);
$rows_per_page = 5;
$total_pages = ceil($total_records / $rows_per_page);
if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
$start_from = ($page - 1) * $rows_per_page;
$sql_friend = sprintf(
  "SELECT f.profile_name, f.friend_id from friends f inner join myfriends s on s.friend_id2= f.friend_id WHERE s.friend_id1 ='%s' LIMIT %d,%d",
  $conn->real_escape_string($curentUser['friend_id']),
  $start_from,
  $rows_per_page
);
$result1 = $conn->query($sql_friend);

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>FriendList</title>
</head>
<div class="container bg-light">
  <?php
  include("nav.php");
  ?>

  <body>
    <br>
    <h2>
      <?php echo preprocess($name) ?>'s Friends
    </h2>

    <span class="d-flex justify-content-end">Total friends:
      <?= preprocess($num_rows) ?>
    </span>
    <?php
    echo '<table class="table table-striped table-light">';
    echo '<tr>
        <th scope="col">Profile Name</th>
        <th scope="col">Action</th>
      </tr>';

    while ($friend_list = $result1->fetch_assoc()) {
      echo '<tr>';
      echo '<td>' . $friend_list['profile_name'] . '</td>';
      echo '<td>
          <form method="post" action="delete.php">
            <input type="hidden" name="user_id" value="' . $curentUser['friend_id'] . '">
            <input type="hidden" name="friend_id" value="' . $friend_list['friend_id'] . '">
            <button type="submit" name="submit" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg"
                width="16" height="16" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                <path
                  d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z" />
                <path
                  d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z" />
              </svg></button>
          </form>
        </td>';
      echo '</tr>';
    }
    echo '</table>';
    //show paginate
    echo '
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">';

    if ($page > 1) {
      echo "<li class='page-item'><a class='page-link' href='friendlist.php?page=" . max(1, $page - 1) . "'>Prev</a>
        </li>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
      echo "<li class='page-item'><a class='page-link' href='friendlist.php?page=" . $i . "'";
      echo ">" . $i
        . "</a></li>";
    }
    if ($total_pages > $page) {
      echo "<li class='page-item'><a class='page-link'
              href='friendlist.php?page=" . min($total_pages, $page + 1) . "'>Next</a></li>";
    }
    echo '
      </ul>
    </nav>
    ';
    ?>
</div>
</body>

</html>