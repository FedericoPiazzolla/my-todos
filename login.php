<?php
function login($username, $password, $connection)
{
  if (!isset($_SESSION)) {
    session_start();
  }

  $hashed_passwd = md5($password);

  $stmt = $connection->prepare("SELECT* FROM `utenti` WHERE `username` = ? AND `password` = ?");
  $stmt->bind_param('ss', $username, $hashed_passwd);
  $stmt->execute();

  $results = $stmt->get_result();

  if ($results->num_rows > 0) {
    $row = $results->fetch_assoc();
    $_SESSION['user_id'] = $row['ID'];
    $_SESSION['username'] = $row['username'];
  } else {
    echo 'ERRORE NEL LOGIN';
  }
}
