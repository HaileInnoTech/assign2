<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Page</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container bg-light">
    <?php
    include("nav.php");
    echo "<div class='container bg-light'>";
    echo '<p><h2 class="text-center">My Friend System</h2></p>';
    echo ' <div class="row">';
    echo '<div class="col">
        <p><strong>Req 1</strong></p>
      <p>What tasks you have not attempted or not completed?</p>';
    echo '<ul>';
    echo '<li>In this assignment I have completed all the Tasks.</li>'; //PHP function to check version
    echo '</ul>';
    ; //PHP function to check version
    echo '<p>What special features have you done, or attempted, in creating the site that we should know about?</p>
    <ul>
    <li>If the users logined successfully, users cannot acces the signup or login page unless they need to log out first.    
    </li>
    <li>There is a navigation bar changed based to the login status.
    </li>
    <li>The system has pasgination for both friendadd and friendlist pages.
    </li>
    </ul>';
    echo '<p>Which parts did you have trouble with?</p>
        <ul>
    <li>I had the trouble when check if an email already exists in the database. I had to use the try catch exception for the dupplicated entry error and display it in an error message which I think it is still not optimised.     
    </li>
    </ul>    
        </div>';
    echo '</div>';

    echo '<p>What would you like to do better next time?</p>
        <ul>
    <li>I would like to write all the sql statement in oob format that I can reusale them by just calling the method as well as make it more secure     
    </li>
    <li>The password that inserted in the table is not secured yet, I would like to hash them next time.
    </li>
    </ul>';

    echo '<p>What additional features did you add to the assignment? </p>
        <ul>
    <li>Use Boostrap for reactive website.
    </li>
    <li>Add pagination for the friendlist.php
    </li>
    <li>Reactive Navigation base on login status
    </li>
    </ul>';
    echo '<p>Listing of links to the following pages</p>
        <ul>
    <li><a href="index.php" ><u>Return to Home Page</u></a></li>
    <li><a href="friendlist.php" ><u>Return to Friend List Page</u></a></li>
    <li><a href="friendadd.php" ><u>Return to Friend Add Page</u></a></li>
    </ul>';

    echo ' <div class = "row">
    <div class ="col">
    <p><strong>Req 2</strong></p>
    <p>What discussion points did you participated on in the unit’s discussion board for Assignment 2</p>
    <img id="image" src="Img/screenshot.png"width="400px" height="400px" alt="Screenshot">
    </div> 
    </div>';
    ?>
  </div>
</body>

</html>