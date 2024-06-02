<?php

//check session if user already loggined
session_start();
if (isset($_SESSION["loggedin"])) {
  header('Location: friendlist.php');
  exit;
}
require_once('data_process.php');

//validattion process
if (isset($_POST['submit'])) {
  $errors = array();
  $email = preprocess($_POST['email']);
  $password = preprocess($_POST['password']);
  if (empty($email)) {
    $errors[] = ('Email is required');
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = ('Invalid Email');
  }
  ;
  if (empty($password)) {
    $errors[] = ('Password is required');
  }
  if (empty($errors)) {
    require_once('db_config.php');
    $sql = sprintf("SELECT * FROM friends WHERE friend_email ='%s'", $conn->real_escape_string($email));
    $result = executeSQLStatement($host, $user, $pswd, $dbnm, $sql);
    $user = $result->fetch_assoc();
    if ($user) {
      if ($password === $user['password']) {
        session_regenerate_id(); //regenerate a new id for session
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $user['profile_name'];
        mysqli_close($conn);
        // Redirect to 'friendadd.php'
        header('Location: friendlist.php');
      } else {
        $errors[] = ('Wrong email or password');
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>Login Page</title>
</head>

<body>
  <div class="container bg-light">
    <?php include("nav.php") ?>
    <h1>My Friend System</h1>
    <h2> Login Page</h2>
    <!--/form self calling -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" novalidate>
      <div>
        <label for="email" class="form-label">Email Address:</label>
        <input type="text" class="form-control" id="email" name="email"
          value="<?= preprocess(isset($_POST['email']) ? $_POST['email'] : "") ?>">
      </div>
      <div class="pb-3">
        <label for=" password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <?php
      if (!empty($errors)) {
        foreach ($errors as $error) {
          echo "<div  class='alert alert-danger px-2 py-2' role='alert'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-exclamation-triangle' viewBox='0 0 16 16'>
                        <path d='M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z'/>
                        <path d='M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z'/>
                        </svg> $error</div>";
        }
      }

      echo "
            <div  class='d-flex justify-content-around align-items-end'>
            <div>
            <input type='submit' name='submit' class='btn btn-primary mt-3' value='Log in'>
                </div>";
      echo "
                <div>
                <button class='btn btn-danger mt-3'><a href='index.php' 
                style='color: white;text-decoration-line:none;'>Exit</a>
                </button>
                </div>
                </div>";

      ?>
    </form>
  </div>
</body>

</html>