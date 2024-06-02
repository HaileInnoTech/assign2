<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="Style/header.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
    integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
  <!-- Navbar -->
  <header class="d-flex justify-content-between">
    <div>
      <h3>
        <?php
        switch ($_SERVER['PHP_SELF']) {
          case '/assign2/about.php':
            echo 'About';
            break;
          case '/assign2/friendadd.php':
            echo 'Friend Add';
            break;
          case '/assign2/friendlist.php':
            echo 'Friend List';
            break;
          case '/assign2/index.php':
            echo 'Index';
            break;
          case '/assign2/login.php':
            echo 'Login';
            break;
          case '/assign2/signup.php':
            echo 'Register';
            break;
          default:
            echo 'Unknown Page';
            break;
        }
        ?>
      </h3>
    </div>

    <div><a href="index.php">
        <h3>My Friend System</h3>
      </a>
    </div>


    <div class="">
      <nav>
        <ul>
          <?php
          if (isset($_SESSION["loggedin"])) {
            $name = $_SESSION['name'];
            echo "
                        <div class='btn-group'>
                        <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-circle' viewBox='0 0 16 16'>
                        <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z'/>
                        <path fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z'/>
                        </svg>  " . $name . "
                        </button>
                        <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' href='index.php'>Index</a></li>
                        <li><a class='dropdown-item' href='friendlist.php'>Friend List</a></li>
                        <li><a class='dropdown-item'href='friendadd.php'>Add friend</a></li>
                        <li><a class='dropdown-item border-top' style='color:red' href='logout.php'>Log out</a></li>
                        </ul>
                        </div>
                            ";
          } else {
            echo "
                    <li><a href='about.php'>About</a></li>
                    <li><a href='login.php'>Login</a></li>
                    <li><a href='signup.php'>Sign Up</a></li>";
          }
          ?>
        </ul>
      </nav>
    </div>
  </header>
</body>


</html>