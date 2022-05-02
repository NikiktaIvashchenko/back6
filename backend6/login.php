<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
    setcookie('login_in', '1');
  // Делаем перенаправление на форму.
  header('Location: form.php');
}
else{
    setcookie('login_in', '0');
}
$err = 0;
if($err==1){
    echo "Неверный лоин или пароль,повторите попытку";
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>


<style>
    body{
    background-image: url(http://beloweb.ru/wp-content/uploads/2014/04/123456729.jpg);
    background-size: 100%;
    background-size: cover;
    color: white;
}
.form_auth_block{
    width: 500px;
    height: 500px;
    margin: 0 auto;
    background-size: cover;
    border-radius: 4px;
}
.form_auth_block_content{
  padding-top: 15%;
}
.form_auth_block_head_text{
    display: block;
    text-align: center;
    padding: 10px;
    font-size: 20px;
    font-weight: 600;
    opacity: 0.7;
}
.form_auth_block input{
  display: block;
  margin: 0 auto;
  width: 80%;
  height: 45px;
  border-radius: 10px;  
  border:none;
  outline: none;
}
input:focus {
  color: #000000;
  border-radius: 10px;
  border: 2px solid #436fea;
}
.form_auth_button{
    display: block;
    width: 80%;
    margin: 0 auto;
    margin-top: 10px;
    border-radius: 10px;
    height: 35px;
    border: none;
    cursor: pointer;
}
.inp{
   border: 4px solid black;
}
</style>

<div class="form_auth_block">
  <div class="form_auth_block_content">
    <p class="form_auth_block_head_text">Авторизация</p>
    <form class="form_auth_style" action="#" method="post">
      <input class="inp" name="login" placeholder="Введите login" required ><br>
      <input class="inp" type="password" name="pass" placeholder="Введите пароль" required >
      <button class="form_auth_button" type="submit" name="form_auth_submit">Войти</button>
    </form>
  </div>
</div>

<?php
}

// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
    $pas = $_POST['pass'];
    $log = $_POST['login'];
  $db_host = "localhost"; 
    $db_user = "u20985"; // Логин БД
    $db_password = "5087148"; // Пароль БД
    $db_base = 'u20985'; // Имя БД
    $db_table = "login"; // Имя Таблицы БД
    $result = false;
        try {
        // Подключение к базе данных
        $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
        // Устанавливаем корректную кодировку
        $db->exec("set names utf8");
        // Собираем данные для запроса
       // $data = array( 'name' => $name,'email' => $email,'date' => $date,'floor' => $floor,'limbs' => $limbs,'superpower' => $superpower,'bio' => $bio,'chek' => $chek ); 
        // Подготавливаем SQL-запрос
        $query = $db->prepare("SELECT login,password FROM login where login='$log'");
        // Выполняем запрос с данными
            $query->execute();
        $ans = $query->fetchAll();
        // Запишим в переменую, что запрос отрабтал
            
    foreach ($ans as $result_row) {
        if($result_row['login']==$log && $result_row['password']==md5($pas)){
            $result = true;
        }
    }
    } catch (PDOException $e) {
        // Если есть ошибка соединения или выполнения запроса, выводим её
        print "Ошибка!: " . $e->getMessage() . "<br/>";
    }
    if ($result) {
        
        
    	echo "лог и пас верны";
         // Если все ок, то авторизуем пользователя.
        $_SESSION['login'] = $_POST['login'];
        // Записываем ID пользователя.
        // Подключение к базе данных
        
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
        $query = $db->prepare("SELECT id FROM $db_table WHERE login ='$log'");
        // Выполняем запрос с данными
        $query->execute();
        $ans = $query->fetchAll();
        foreach ($ans as $result_row) {
             $_SESSION['uid'] = $result_row['id'];
        }
        $err = 0;
    }
    else{
        echo "Неверный логин или пароль";
        header('Location: login.php');
        $err = 1;
        exit();
    }
    
  // Делаем перенаправление.
  header('Location: ./');
}
