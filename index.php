<?php
// Collegamento al mysql
define("DB_SERVER", "localhost:8889");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "todo_list");

$connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($connection && $connection->connect_error) {
  echo "Connection Failed";
  echo $connection->connect_error;
  die;
};

$sql = "SELECT `id`, `todo`, `status`, `user_id`  FROM `todos`";
$result = $connection->query($sql);

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
  <?php if($result && $result->num_rows > 0) { ?>
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
      <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <th scope="row"><?php echo $row['id'] ?></th>
        <td><?php echo $row['todo'] ?></td>
        <td><?php echo $row['status'] ?></td>
        <td><?php echo $row['user_id'] ?></td>
      </tr>
      </tr>
    </tbody>
    <?php } ?>
  </table>
  <?php } ?>
</body>

</html>