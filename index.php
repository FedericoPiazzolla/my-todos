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
  $sql = "SELECT `id`, `nome_todo`, `status`, `user_id` FROM `todo_list` WHERE `user_id` = '$user_id'";
  $results = $connection->query($sql);
};


// inserisco il nuovo todo
if (!empty($_POST['newtodo'])) {
  $new_todo = $_POST['newtodo'];

  $query = "INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`) VALUES (NULL, '$new_todo', '0', '$user_id')";
  $connection->query($query);

  header("Location: index.php?newTodo=success");
};

// query elimina todo
if (isset($_POST['delete'])) {
  $delete_todo = $_POST['delete'];

  $query_delete = "DELETE FROM `todo_list` WHERE `todo_list`.`id` = $delete_todo";
  $connection->query($query_delete);
  header("Location: index.php?deleteToDo=success");
};

// query per cambiare status alla li del to do
if (isset($_POST['toggle_todo'])) {
  $todoID = $_POST['toggle_todo'];

  // aggiorno lo status
  $query_status = "UPDATE todo_list SET status = NOT status WHERE id = $todoID";
  $connection->query($query_status);
  header("Location: index.php");
};

$connection->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>To-Do-List</title>

  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <!-- fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <?php include_once __DIR__ . "/partials/header.php"; ?>

  <div class="container my-5">

    <!-- verifico de l'utente è loggato correttamente, user_id e username siano corretti -->
    <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>

      <?php if ($results && $results->num_rows >= 0) { ?>

        <!-- ciclo while per mostrare gli elementi del db in una tabella -->
        <ul class="list-group">
          <?php while ($row = $results->fetch_assoc()) { ?>

            <li class="list-group-item d-flex">
              <form action="index.php" method="POST" class="p-1">
                <input type="hidden" name="toggle_todo" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-outline-secondary border-0"><i class="fa-regular fa-circle-check"></i></button>
              </form>
              <p class="p-2 flex-grow-1 <?php if ($row['status'] == 1) { echo "text-decoration-line-through"; } ?>"><?php echo $row['nome_todo'] ?></p>
              <p class="p-2"><?php echo $row['user_id'] ?></p>
              <form action="index.php" method="POST" class="p-2">
                <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="delete">
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
              </form>
            </li>
          <?php } ?>
        </ul>

        <!-- ADD NEW TODO -->
        <h2 class="text-center pt-5">NEW TO-DO</h2>

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
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Invia</button>
          </form>
        </div>
        <a href="./subscribe.php" class="btn btn-danger">Registrati</a>
      </div>

    <?php } ?>

  </div>

</body>

</html>