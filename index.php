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


      case 'oauth': // oauth авторизация
        if (!isset($_GET['google_id'])) {
          mysqli_close($link); // Закрываем подключение MySQL и
          die("<h1>Error 901</h1> Something's missing..."); // Выводим ошибку 901
        }
        // выполняем операции с базой данных
        $query ="SELECT id, token FROM users WHERE google_id = ".$_GET['google_id'];
        $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row); // Выводим json строку с результатом
        break;


        case 'reg':
          if (!isset($_GET['username']) || !isset($_GET['nick']) || !isset($_GET['age']) || !isset($_GET['google_id'])) {
            die("<h1>Error 901</h1> Something's missing..."); // Выводим ошибку 901
          }
          $ip = $_SERVER['REMOTE_ADDR'];
          $length = 32;
          $chartypes = "lower,upper,numbers";
          $token = random_string($length, $chartypes);
          // выполняем операции с базой данных
          $query = "INSERT INTO users (username, nick, age, google_id, token, reg_ip) VALUES ('".$_GET['username']."', '".$_GET['nick']."', '".$_GET['age']."', '".$_GET['google_id']."', '".$token."', '".$ip."')";
          $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
          $query ="SELECT id, token FROM users WHERE google_id = ".$_GET['google_id'];
          $result = mysqli_query($link, $query) or die("<h1>Error 902</h1> MySQL Error.");
          $row = mysqli_fetch_assoc($result);
          echo json_encode($row); // Выводим json строку с результатом
          break;


    default: // Иначе
      mysqli_close($link); // Закрываем подключение MySQL и
      die("<h1>Error 904</h1> Method not found."); // выводим ошибку 904
      break;
  }


  function random_string($length, $chartypes)
{
    $chartypes_array=explode(",", $chartypes);
    // задаем строки символов.
    //Здесь вы можете редактировать наборы символов при необходимости
    $lower = 'abcdefghijklmnopqrstuvwxyz'; // lowercase
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase
    $numbers = '1234567890'; // numbers
    $special = '^@*+-+%()!?'; //special characters
    $chars = "";
    // определяем на основе полученных параметров,
    //из чего будет сгенерирована наша строка.
    if (in_array('all', $chartypes_array)) {
        $chars = $lower . $upper. $numbers . $special;
    } else {
        if(in_array('lower', $chartypes_array))
            $chars = $lower;
        if(in_array('upper', $chartypes_array))
            $chars .= $upper;
        if(in_array('numbers', $chartypes_array))
            $chars .= $numbers;
        if(in_array('special', $chartypes_array))
            $chars .= $special;
    }
    // длина строки с символами
    $chars_length = strlen($chars) - 1;
    // создаем нашу строку,
    //извлекаем из строки $chars символ со случайным
    //номером от 0 до длины самой строки
    $string = $chars{rand(0, $chars_length)};
    // генерируем нашу строку
    for ($i = 1; $i < $length; $i = strlen($string)) {
        // выбираем случайный элемент из строки с допустимыми символами
        $random = $chars{rand(0, $chars_length)};
        // убеждаемся в том, что два символа не будут идти подряд
        if ($random != $string{$i - 1}) $string .= $random;
    }
    // возвращаем результат
    return $string;
}
?>
