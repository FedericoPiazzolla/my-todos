<?php 
  if (!isset($_SESSION)) {
    session_start();
  };

  if (isset($_POST['logout']) && isset($_POST['logout']) === "1") {
    session_destroy();
    header("Location: index.php?logout=success");
  };
?>