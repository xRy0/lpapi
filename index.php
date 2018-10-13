<?php

//  Copyright (c) 2018 Copyright ParadiseFox All Rights Reserved.

    if($_SERVER['SERVER_PORT'] != 443) {
        die("<h1>Error 900</h1> Not https.");
    };

  $method = $_GET['method'];

  $host = "localhost";       // Host mysql
  $user = "qq";       // User mysql
  $password = "qwe";   // User password mysql
  $database = "lp";   // DataBase name

  $link = mysqli_connect($host, $user, $password, $database) or die("<h1>Error 902</h1> MySQL Error.");

  switch ($method) {
    case 'user.get':
       if (!isset($_GET['id'])) {
         mysqli_close($link);
         die("<h1>Error 901</h1> Something's missing...");
       }
       // выполняем операции с базой данных
        $query ="SELECT * FROM users WHERE id = ".$_GET['id'];
        $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
        $row = mysqli_fetch_assoc($result);
        if($result)
        {
          echo json_encode($row);
        }
      break;

    default:
      mysqli_close($link);
      die("<h1>Error 904</h1> Method not found.");
      break;
  }
?>
