<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Home Page</title>
</head>

<body>
  <div class="container bg-light">
    <?php
    session_start();
    include("nav.php");
    ?>
    <br>
    <h1>My Friend System</h1>
    <p>Name: Hai Hoang Le</p>
    <p>Student Id: 103542974 </p>
    <p>Email: <a href="103542974@student.swin.edu.au"> <u>103542974@student.swin.edu.au</u></a></p>
    <p>I declare that this assignment is my individual work. I have not worked
      collaboratively nor have I copied from any other students work or from any other source.</p>

    <?php
    require_once('db_config.php');
    $check = true;
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS $dbnm";
    if ($conn->query($sql) === FALSE) {
      echo "Error creating database: " . $conn->error;
      $check = false;

    }
    //Select db
    $conn->select_db($dbnm);

    //create friend table 
    $sql = "CREATE TABLE IF NOT EXISTS friends (
        friend_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        friend_email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(20) NOT NULL,
        profile_name VARCHAR(30) NOT NULL,
        date_started DATE NOT NULL,
        num_of_friends INTEGER UNSIGNED
)";
    if ($conn->query($sql) === FALSE) {
      echo "Error creating table: " . $conn->error;
      $check = false;

    }

    // Create myfriends table
    $sql = "CREATE TABLE IF NOT EXISTS myfriends (
    friend_id1 INTEGER NOT NULL,
    friend_id2 INTEGER NOT NULL,
    FOREIGN KEY (friend_id1) REFERENCES friends(friend_id),
    FOREIGN KEY (friend_id2) REFERENCES friends(friend_id),
    CHECK (friend_id1 <> friend_id2)
)";
    if ($conn->query($sql) === FALSE) {
      echo "Error creating table: " . $conn->error;
      $check = false;

    }

    $result = "SELECT * from friends ";
    $result = $conn->query($result);
    if ($result->num_rows == 0) {
      //test data for friends table
      $sql = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
            VALUES
            ('bobsmith@yahoo.com', 'qwerty', 'Bob Smith', '2021-12-31', 0),
            ('alicejones@hotmail.com', 'mypassword', 'Alice Jones', '2022-02-14', 0),
            ('davidlee@gmail.com', 'password1', 'David Lee', '2022-04-01', 0),
            ('sarahkim@yahoo.com', 'password2', 'Sarah Kim', '2022-05-10', 0),
            ('michaelbrown@gmail.com', 'password3', 'Michael Brown', '2022-06-01', 0),
            ('emilypark@hotmail.com', 'password4', 'Emily Park', '2022-07-01', 0),
            ('richardchen@hotmail.com', 'password5', 'Richard Chen', '2022-08-01', 0),
            ('amandawong@gmail.com', 'password6', 'Amanda Wong', '2022-09-01', 0),
            ('jenny1978@hotmail.com', 'j3nnYecr3t', 'Jennifer Johnson', '1978-11-02', 0),
            ('michael.smith@example.com', 'm1k3y23', 'Michael Smith', '1990-05-12', 0),
            ('haile@gmail.com', 'hoanghai2002', 'haile', '1990-05-12', 0),
            ('datngo@gmail.com', 'hoanghai2002', 'datngo', '1990-05-12', 0),
            ('minhtran@gmail.com', 'hoanghai2002', 'minhtran', '1990-05-12', 0),
            ('nhatanh@gmail.com', 'hoanghai2002', 'nhatanh', '1990-05-12', 0)";

      if ($conn->query($sql) === FALSE) {
        echo "Error inserting data: " . $conn->error;
        $check = false;
      }
      ;
    }
    //test data for myfriends table
    $result = "SELECT * from myfriends";
    $result = $conn->query($result);
    if ($result->num_rows == 0) {
      $sql = "INSERT INTO myfriends (friend_id1, friend_id2)
        VALUES
        (1, 2),
        (2,1),
        (1,3),
        (3,1),
        (1, 4),
        (4,1),
        (2, 5),
        (5, 2),
        (2, 6),
        (6, 2),
        (3, 7),
        (7, 3),
        (4, 7),
        (7, 4),
        (4, 9),
        (9,4),
        (4, 6),
        (6,4),
        (5, 4),
        (4, 5),
        (5, 1),
        (1,5),
        (6, 4),
        (4,6),
        (7, 8),
        (8,7),
        (7, 6),
        (6,7),
        (8, 3),
        (3,8),
        (8, 1),
        (1,8),
        (9, 8),
        (8,9),
        (10, 5),
        (5,10),
        (10, 4),
        (4,10),
        (9, 2),
        (2,9)";
      if ($conn->query($sql) === FALSE) {
        echo "Error inserting data: " . $conn->error;
        $check = false;
      }
    }

    $conn->close();
    if ($check === true) {
      echo "<p>Table successfully created and populated</p>";
    }
    ?>
  </div>
</body>

</html>