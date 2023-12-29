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
} else {
  echo "i campi devono essere compilati";
};

$connection->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-5">

    <h2 class="text-center">Subscribe</h2>

    <div class="card w-50 mx-auto">
      <div class="card-body">

        <form class="row g-3" action="subscribe.php" method="POST">
          <div class="col-md-6">
            <label for="in-name" class="form-label">username</label>
            <input type="text" class="form-control" id="in-name" name="in-name">
          </div>
          <div class="col-md-6">
            <label for="in-password" class="form-label">password</label>
            <input type="password" class="form-control" id="in-password" name="in-password">
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Sign in</button>
          </div>
        </form>

      </div>

    </div>

  </div>
</body>

</html>