<?php
//check session if user already loggined
session_start();
if (isset($_SESSION["loggedin"])) {
  header('Location: friendlist.php');
  exit;
}
require_once('data_process.php');

//form input validate
if (isset($_POST['submit'])) {
  $errors = array();
  $name = preprocess($_POST['name']);
  $email = preprocess($_POST['email']);
  $password = preprocess($_POST['password']);
  $confirmpassword = preprocess($_POST['confirmpassword']);


  if (empty($name)) {
    $errors[] = 'Name is required';
  } else if (!preg_match("/^[a-zA-Z' -]+$/", $name)) {
    $errors[] = 'Invalid Name';
  }

  if (empty($email)) {
    $errors[] = ('Email is required');
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = ('Invalid Email');
  }
  ;

  if (empty($password)) {
    $errors[] = ('Password is required');
  } else if (!preg_match("/^(?=.*\d).{6,}$/", $password)) {
    $errors[] = ('Password must contains 6 characters and 1 digit');
  }


  if (empty($confirmpassword)) {
    $errors[] = ('Confirmpassword is required');
  } else if ($confirmpassword !== $password) {
    $errors[] = ('Passwords must match');
  }

  if (empty($errors)) {
    require_once('db_config.php');
    $table = "friends";
    $sql = "INSERT INTO $table (friend_email, password, profile_name, date_started, num_of_friends) VALUES (?, ?, ?, NOW(), 0)";

    // Bind parameters to pass to the executeSQLStatement function
    $params = array("sss", $email, $password, $name);

    // Call the function to execute the SQL statement and check for email duplication
    $email_duplicated = checkEmailDuplicate($host, $user, $pswd, $dbnm, $sql, $params);

    // Check if email duplication occurred
    if ($email_duplicated) {
      $errors[] = 'Email already exists';
    }

    //gnerate new session if no errors
    if (empty($errors)) {
      session_regenerate_id();
      $_SESSION['loggedin'] = true;
      $_SESSION['name'] = $name;
      header('Location: friendadd.php');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>Sign Up Page</title>

</head>

<body>
  <div class="container bg-light">
    <?php include("nav.php") ?>
    <h1>My Friend System</h1>
    <h2> Registration Page</h2>
    <!--/form self calling -->
    <form action="" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" novalidate>
      <div>
        <label for=" name" class="form-label">Name:</label>
        <input type="text" id="name" name="name" class="form-control"
          value="<?= preprocess(isset($_POST['name']) ? $_POST['name'] : "") ?>">
      </div>
      <div> <label for="email" class="form-label">Email Address:</label>
        <input type="text" id="email" name="email" class="form-control" <input type="text" id="name" name="name"
          class="form-control" value="<?= preprocess(isset($_POST['email']) ? $_POST['email'] : "") ?>">
      </div>
      <div>
        <label for=" password" class="form-label">Password:</label>
        <input type="password" id="password" class="form-control" name="password">
      </div>
      <div class="pb-3">
        <label for="confirmpassword" class="form-label">Confirm Password</label>
        <input type="password" id="confirmpassword" class="form-control" name="confirmpassword">
      </div>
      <?php
      if (!empty($errors)) {
        foreach ($errors as $error) {
          echo "<div  class='alert alert-danger px-2 py-2 d-flex justify-content-start' role='alert'>
          <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-exclamation-triangle' viewBox='0 0 16 16'>
          <path d='M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z'/>
          <path d='M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z'/>
        </svg> $error</div>";
        }
      }
      echo "
      <div  class='d-flex justify-content-around align-items-end pb-3'>
      <div>
      <input type='submit' name='submit' class='btn btn-primary mt-3' value='Register'>
          </div>";
      echo "
        <div>        <input type='reset' id='reset' value='Clear' class='btn btn-warning'>
        </div
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