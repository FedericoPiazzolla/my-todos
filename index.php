<?php
require_once __DIR__ . "/login.php";

// creo una sessione per il login
if (!isset($_SESSION)) {
  session_start();
};

// Collegamento al mysql
define("DB_SERVER", "localhost:8889");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "todo_list");

// stabilire la connessione
$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// se la connessione non è avvenuta
if ($connection && $connection->connect_error) {
  echo "Connection Failed";
  echo $connection->connect_error;
  die;
};

// verifico i dati dell'operazione di login
if (isset($_POST['username']) && isset($_POST['password'])) {
  login($_POST['username'], $_POST['password'], $connection);
};

// query di visualizzazione del contenuto (privato per user_id)
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT `id`, `nome_todo`, `status`, `user_id`  FROM `todo_list` WHERE `user_id` = '$user_id'";
  $results = $connection->query($sql);
};


// inserisco il nuovo todo
if (!empty($_POST['newtodo'])) {
  $new_todo = $_POST['newtodo'];

  $query = "INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`) VALUES (NULL, '$new_todo', '0', '$user_id')";
  $connection->query($query);

  header("Location: index.php?newTodo=success");
};

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To-Do-List</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <?php include_once __DIR__ . "/partials/header.php"; ?>

  <div class="container my-5">

    <!-- verifico de l'utente è loggato correttamente, user_id e username siano corretti -->
    <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>

      <?php if ($results && $results->num_rows > 0) { ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">id</th>
              <th scope="col">todos</th>
              <th scope="col">status</th>
              <th scope="col">user_id</th>
            </tr>
          </thead>
          <tbody>
            <!-- ciclo while per mostrare gli elementi del db in una tabella -->
            <?php while ($row = $results->fetch_assoc()) { ?>
              <tr>
                <th scope="row"><?php echo $row['id'] ?></th>
                <td><?php echo $row['nome_todo'] ?></td>
                <td><?php echo $row['status'] ?></td>
                <td><?php echo $row['user_id'] ?></td>
              </tr>
              </tr>
          </tbody>
        <?php } ?>
        </table>

        <!-- ADD NEW TODO -->
        <h2 class="text-center">NEW TO-DO</h2>

        <form class="row g-3 align-items-end" action="index.php" method="POST">
          <div class="col-10">
            <label for="inputNewTodo" class="form-label">New To-Do</label>
            <input type="text" class="form-control" id="newtodo" name="newtodo">
          </div>
          <div class="col-2">
            <button type="submit" class="btn btn-primary">ADD</button>
          </div>
        </form>

      <?php } ?>


    <?php } else { ?>

      <!-- Se arrivo da logout stampo il messagio di comunicazione -->
      <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success') { ?>
        <div class="alert alert-success">
          Logout è avvenuto con successo
        </div>
      <?php } ?>

      <!-- se l'utente non ha effettuato il login, dovra compilare i campi e registrarsi  -->
      <h2 class="text-center">LOGIN</h2>

      <div class="card w-50 mx-auto">
        <div class="card-body">
          <form action="index.php" method="POST">

            <div class="mb-3">
              <label for="name" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Invia</buttn>
          </form>
        </div>
        <a href="./subscribe.php" class="btn btn-danger">Registrati</a>
      </div>

    <?php } ?>

  </div>

</body>

</html>