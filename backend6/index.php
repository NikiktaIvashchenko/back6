<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);//variant
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены11.';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['name1'] = !empty($_COOKIE['name1_error']);
    $errors['e-mail'] = !empty($_COOKIE['e-mail_error']);
    $errors['date'] = !empty($_COOKIE['date_error']);
    $errors['floor'] = !empty($_COOKIE['floor_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
  // TODO: аналогично все поля.

if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error_mes">Заполните имя.</div>';
  }
    if ($errors['name1']) {
    setcookie('name1_error', '', 100000);
    $messages[] = '<div class="error_mes">Перезапишите имя без цифр.</div>';
  }
    if ($errors['e-mail']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('e-mail_error', '', 100000);
    $messages[] = '<div class="error_mes">Заполните email.</div>';
  }
    if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error_mes">Заполните дату.</div>';
  }
    if ($errors['floor']) {
    setcookie('floor_error', '', 100000);
    $messages[] = '<div class="error_mes">Заполните пол.</div>';
  }
    if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error_mes">Заполните биографию.</div>';
  }
  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
     $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['e-mail'] = empty($_COOKIE['e-mail_value']) ? '' : strip_tags($_COOKIE['e-mail_value']);
     $values['date'] = empty($_COOKIE['date_value']) ? '' : strip_tags($_COOKIE['date_value']);
     $values['floor'] = empty($_COOKIE['floor_value']) ? '' : strip_tags($_COOKIE['floor_value']);
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
      
       $pas = $_POST['pass'];
    $log = $_POST['login'];
  $db_host = "localhost"; 
    $db_user = "u20985"; // Логин БД
    $db_password = "5087148"; // Пароль БД
    $db_base = 'u20985'; // Имя БД
    $db_table = "login"; // Имя Таблицы БД
    $result = false;
      $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
        // Устанавливаем корректную кодировку
        $db->exec("set names utf8");
        // Собираем данные для запроса
       // $data = array( 'name' => $name,'email' => $email,'date' => $date,'floor' => $floor,'limbs' => $limbs,'superpower' => $superpower,'bio' => $bio,'chek' => $chek ); 
        // Подготавливаем SQL-запрос
        $id = $query = $db->prepare("SELECT * FROM $db_table WHERE login = '$log'");
        // Выполняем запрос с данными
        $query->execute();
      $ans = $query->fetchAll();
        foreach ($ans as $result_row) {
            $values['name'] = $result_row['name'];
  $values['e-mail'] =$result_row['e-mail'];
     $values['date'] =$result_row['date'];
     $values['floor'] = $result_row['floor'];
    $values['bio'] = $result_row['bio'];
        }
      
      
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  $pattern1 = '#[0-9]+#';
  if (empty($_POST['name'])) {
    // Выдаем куку на день с флажком об ошибке в поле name.
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
      if( !preg_match($pattern1, $_POST['name'])){
              setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
      }
      else{
           setcookie('name1_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
      }
  }

    if (empty($_POST['e-mail'])) {
    setcookie('e-mail_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('e-mail_value', $_POST['e-mail'], time() + 30 * 24 * 60 * 60);
  }

    if (empty($_POST['date'])) {

    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  }

    if (empty($_POST['floor'])) {
    setcookie('floor_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('floor_value', $_POST['floor'], time() + 30 * 24 * 60 * 60);
  }

    if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }


  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
      echo("<script>console.log('net errors');</script>");
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
      setcookie('e-mail_error', '', 100000);
      setcookie('date_error', '', 100000);
      setcookie('floor_error', '', 100000);
      setcookie('bio_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
      echo 'login chek ';
      echo $_SESSION['login'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $floor = $_POST['floor'];
    $limbs = $_POST['limbs'];
    $superpower = $_POST['superpower'];
    $bio = $_POST['bio'];
    $email = $_POST['e-mail'];
    if(isset($_POST['chek'])){
        $chek = $_POST['chek'];
    }
    else{
        $chek = 'no';
    }
      $log = $_SESSION['login'];
      echo "log pri update";//uuuuuuuuuuuu
      echo $log;
    
   $db_host = "localhost"; 
    $db_user = "u20985"; // Логин БД
    $db_password = "5087148"; // Пароль БД
    $db_base = 'u20985'; // Имя БД
    $db_table = "login"; // Имя Таблицы БД
    
        try {
        // Подключение к базе данных
        $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
        // Устанавливаем корректную кодировку
        $db->exec("set names utf8");
        // Собираем данные для запроса
        $data = array( 'name' => $name,'email' => $email,'date' => $date,'floor' => $floor,'limbs' => $limbs,'superpower' => $superpower,'bio' => $bio,'chek' => $chek ); 
        // Подготавливаем SQL-запросUPDATE books SET price=263.00, discount=10, amount=amount-2 WHERE id=3;

        $query = $db->prepare("UPDATE $db_table SET name=:name,mail=:email,date=:date,sex=:floor,limbs=:limbs,superpower=:superpower,bio=:bio,consent=:chek WHERE login ='$log'");
        // Выполняем запрос с данными
        $query->execute($data);
        // Запишим в переменую, что запрос отрабтал
        $result = true;
    } catch (PDOException $e) {
        // Если есть ошибка соединения или выполнения запроса, выводим её
        print "Ошибка!: " . $e->getMessage() . "<br/>";
    }
    if ($result) {
    	echo "Успех. Информация обновлена";
    }
  }
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = uniqid();
    $pass = rand(1000000,9999999);
    
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);
    $pass = md5($pass);
    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    $name = $_POST['name'];
    $date = $_POST['date'];
    $floor = $_POST['floor'];
    $limbs = $_POST['limbs'];
    $superpower = $_POST['superpower'];
    $bio = $_POST['bio'];
    $email = $_POST['e-mail'];
    if(isset($_POST['chek'])){
        $chek = $_POST['chek'];
    }
    else{
        $chek = 'no';
    }

    $db_host = "localhost"; 
    $db_user = "u20985"; // Логин БД
    $db_password = "5087148"; // Пароль БД
    $db_base = 'u20985'; // Имя БД
    $db_table = "login"; // Имя Таблицы БД
    
        try {
        // Подключение к базе данных
        $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
        // Устанавливаем корректную кодировку
        $db->exec("set names utf8");
        // Собираем данные для запроса
        $data = array( 'name' => $name,'email' => $email,'date' => $date,'floor' => $floor,'limbs' => $limbs,'superpower' => $superpower,'bio' => $bio,'chek' => $chek,'login' => $login, 'password' => $pass); 
        // Подготавливаем SQL-запрос
        $query = $db->prepare("INSERT INTO $db_table (name,mail,date,sex,limbs,superpower,bio,consent,login,password) VALUES (:name,:email,:date,:floor,:limbs,:superpower,:bio,:chek,:login,:password)");
        // Выполняем запрос с данными
        $query->execute($data);
        // Запишим в переменую, что запрос отрабтал
        $result = true;
    } catch (PDOException $e) {
        // Если есть ошибка соединения или выполнения запроса, выводим её
        print "Ошибка!: " . $e->getMessage() . "<br/>";
    }
    if ($result) {
    	echo "Успех. Информация занесена в базу данных";
    }

  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}
