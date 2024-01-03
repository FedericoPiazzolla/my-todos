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

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($user_id !== null) {
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

// Calcola il numero di To-Do rimanenti direttamente nella query
$sqlRemaining = "SELECT COUNT(*) AS remaining_todos FROM `todo_list` WHERE `user_id` = '$user_id' AND `status` = 0";
$resultRemaining = $connection->query($sqlRemaining);
$rowRemaining = $resultRemaining->fetch_assoc();
$remainingTodos = ($rowRemaining) ? $rowRemaining['remaining_todos'] : 0;

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

  <!-- my_style -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php if (!empty($_SESSION['username']) && !empty($_SESSION['user_id'])) { ?>
    <header>
      <nav class="d-flex justify-content-between ">

        <div>
          <img class="ms_logo" src="img/logo.png" alt="">
        </div>

        <div class="d-flex me-4 align-items-center">
          <span class="px-4">Ciao <?php echo $_SESSION['username']; ?></span>

          <form action="logout.php" method="POST">
            <input type="hidden" type="text" value="1" name="logout">
            <button type="submit" class="btn btn-danger">Logout</button>
          </form>
        </div>

      </nav>
    </header>
  <?php } ?>

  <div class="container py-5 ms_container">

    <!-- verifico de l'utente è loggato correttamente, user_id e username siano corretti -->
    <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['username'])) { ?>

      <?php if ($results && $results->num_rows >= 0) { ?>
        <section id="ms-list_group" class="d-flex flex-column justify-content-between border">
          <div>
            <h2 class="text-center pt-5">MY TO-DO'S</h2>

            <div class="ms-scroll">
              <ul class="list-group ">
                <?php $results->data_seek(0); ?>
                <?php while ($row = $results->fetch_assoc()) { ?>
                  <?php if ($row['status'] == 0) { ?>
                    <li class="list-group-item d-flex">
                      <form action="index.php" method="POST" class="p-1">
                        <input type="hidden" name="toggle_todo" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-outline-secondary border-0"><i class="fa-regular fa-circle-check"></i></button>
                      </form>
                      <p class="p-2 m-0 text-center flex-grow-1"><?php echo $row['nome_todo'] ?></p>
                    </li>
                  <?php } ?>
                <?php } ?>
              </ul>
            </div>

          </div>
          <h4 class="text-center alert alert-info"><?php echo $remainingTodos; ?> to-do's left</h4>
        </section>


        <!-- ADD NEW TODO -->
        <section id="ms-new_todo" class="d-flex flex-column justify-content-between h-100">
          <div class="h-25 border ms_new-div">
            <h2 class="text-center pt-5">NEW TO-DO</h2>

            <form action="index.php" method="POST">
              <div class="input-group mb-3">
                <input type="text" class="form-control" id="newtodo" name="newtodo" aria-label="Recipient's username" aria-describedby="button-addon2" placeholder="write a new to-do">
                <button type="submit" class="btn btn-outline-info" id="button-addon2">ADD</button>
              </div>
            </form>
          </div>

          <!-- To do's Done -->
          <div class="ms_done-todo h-75 border ms-scroll">

            <h2 class="text-center pt-5">TO DO'S DONE</h2>
            <ul class="list-group">
              <?php $results->data_seek(0); ?>
              <?php while ($row = $results->fetch_assoc()) { ?>
                <?php if ($row['status'] == 1) { ?>
                  <li class="list-group-item d-flex">
                    <p class="p-2 m-0 flex-grow-1 text-center <?php if ($row['status'] == 1) {
                                                                echo "text-decoration-line-through";
                                                              } ?>"><?php echo $row['nome_todo'] ?></p>
                    <form action="index.php" method="POST" class="p-1">
                      <input type="hidden" type="text" value="<?php echo $row['id'] ?>" name="delete">
                      <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </li>
                <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </section>

      <?php } ?>


    <?php } else { ?>

      <div class="container">
        <!-- Se arrivo da logout stampo il messagio di comunicazione -->
        <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success') { ?>
          <div class="alert alert-success mb-5 ">
            Logout è avvenuto con successo
          </div>
        <?php } ?>

        <!-- se l'utente non ha effettuato il login, dovra compilare i campi e registrarsi  -->
        

        <div class="card w-50 mx-auto position-relative ms_my-card-login">
          <h2 class="text-center ms_login-title">LOGIN</h2>
          <div class="card-body">
            <form action="index.php" method="POST">

              <div class="input-group input-group-lg flex-nowrap p-3 my-5">
                <span class="input-group-text" id="basic-wrapping"><i class="fa-solid fa-user fa-lg"></i></span>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" aria-label="Username" aria-describedby="basic-wrapping">
              </div>

              <div class="input-group input-group-lg flex-nowrap p-3 mb-5">
                <span class="input-group-text" id="basic-wrapping"><i class="fa-solid fa-lock fa-lg"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" aria-label="Username" aria-describedby="basic-wrapping">
              </div>

              <div class="d-flex justify-content-center">
                <button type="submit" class="m-3 w-100 btn btn-info ms_login-btn">LOGIN</button>
              </div>
              
            </form>
          </div>
          <span class="p-5 text-center text-light">Se non ti sei ancora registrato fallo qui -> <a href="./subscribe.php" class="link-info">Registrati</a></span>
        </div>

      <?php } ?>
      </div>


  </div>

</body>

</html>