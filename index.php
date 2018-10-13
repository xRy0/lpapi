<?php

//  Copyright (c) 2018 Copyright ParadiseFox All Rights Reserved.

    if($_SERVER['SERVER_PORT'] != 443){        // Если подключение не https то
        die("<h1>Error 900</h1> Not https."); // Выводим ошибку 900
    }

  $method = $_GET['method']; // В переменную $method вставляем метод из Get запроса

  $host = "localhost";       // Host mysql
  $user = "qq";              // User mysql
  $password = "qwe";         // User password mysql
  $database = "lp";          // DataBase name

  $link = mysqli_connect($host, $user, $password, $database) or die("<h1>Error 902</h1> MySQL Error."); // Подключаемся к базе данных иначе выводим ошибку 902

  switch ($method) {  // Свитч с методами
    case 'user.get':  // Если метод USER.GET
       if (!isset($_GET['id'])) { // Если нет параметра ID то
         mysqli_close($link); // Закрываем подключение MySQL и
         die("<h1>Error 901</h1> Something's missing..."); // Выводим ошибку 901
       }
       // выполняем операции с базой данных
       $query ="SELECT token FROM users WHERE id = ".$_GET['id'];
       $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
       $row = mysqli_fetch_assoc($result);
       if (isset($_GET['token']) && $_GET['token'] == $row['token']) {
         $query ="SELECT id, username, nick, age, google_id, token FROM users WHERE id = ".$_GET['id'];
       }
       else {
        $query ="SELECT id, username, nick FROM users WHERE id = ".$_GET['id'];
      }
        $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
        $row = mysqli_fetch_assoc($result);
        if($result)
        {
          echo json_encode($row); // Выводим json строку с результатом
        }
      break;

    default: // Иначе
      mysqli_close($link); // Закрываем подключение MySQL и
      die("<h1>Error 904</h1> Method not found."); // выводим ошибку 904
      break;
  }
?>
