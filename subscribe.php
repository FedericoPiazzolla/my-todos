<?php

// collego il mio mysql
define("DB_SERVER", "localhost:8889");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "todo_list");

// stabilisco la connessione
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// se la connessione non è riuscita
if ($connection && $connection->connect_error) {
  echo "Connection failed";
  echo $connection->connect_error;
  die;
};

// prelevo i dati dal form
if (!empty($_POST['in-name']) && !empty($_POST['in-password'])) {
  $username = $_POST['in-name'];
  $password = $_POST['in-password'];
  $ashed_password = md5($password);

  $sql = "INSERT INTO `users` (`ID`, `username`, `password`) VALUES (NULL, '$username', '$ashed_password')";

  if ($connection->query($sql) === FALSE) {
    echo "Errore nell'inserimento dei dati: " . $connection->error;
  }

  header("Location: index.php?subscribe=success");
};

$connection->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <!-- fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- my-style -->
  <link rel="stylesheet" href="css/subscribe.css">
</head>

<body>
  <div class="container mt-5">

    <div class="card w-50 mx-auto position-relative ms_my-card-signin">
      <h2 class="text-center ms_h2-title">SIGN IN</h2>
      <div class="card-body">

        <form class="row g-3" action="subscribe.php" method="POST">
          <div class="input-group input-group-lg flex-nowrap p-3 my-5">
            <span class="input-group-text" id="basic-wrapping"><i class="fa-solid fa-user fa-lg"></i></span>
            <input type="text" class="form-control" id="in-name" name="in-name" placeholder="Username" aria-label="Username" aria-describedby="basic-wrapping">
          </div>
          <div class="input-group input-group-lg flex-nowrap p-3 mb-5">
            <span class="input-group-text" id="basic-wrapping"><i class="fa-solid fa-lock fa-lg"></i></span>
            <input type="password" class="form-control" id="in-password" name="in-password" placeholder="Password" aria-label="Username" aria-describedby="basic-wrapping">
          </div>
          <div class="d-flex justify-content-center">
            <button type="submit" class="m-3 w-100 btn btn-info ms_sign-in-btn">Sign in</button>
          </div>
        </form>

      </div>

    </div>

  </div>
</body>

</html>