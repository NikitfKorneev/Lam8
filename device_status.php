 <?php
//Настройки подключения к БД
define('DB_HOST', 'localhost'); //Адрес
define('DB_USER', 'root'); //Имя пользователя
define('DB_PASSWORD', ''); //Пароль
define('DB_NAME', 'z925998t_db'); //Имя БД
$mysql = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

if(isset($_GET["ID"])){ //Если запрос от устройства содержит идентификатор
$result = mysqli_query($mysql, "SELECT * FROM DEVICE_TABLE WHERE DEVICE_ID='".$_GET['ID']."'");
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$result = mysqli_query($mysql, "SELECT OUT_STATE FROM OUT_STATE_TABLE WHERE DEVICE_ID = '".$_GET['ID']."'");

$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$result = mysqli_query($mysql, "UPDATE OUT_STATE_TABLE SET OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'");

} else { //Если данных для такого устройства нет - добавляем
$result = mysqli_query($mysql, "INSERT OUT_STATE_TABLE SET DEVICE_ID='".$_GET['ID']."', OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today'");
}
}

if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$result = mysqli_query($mysql, "SELECT TEMPERATURE FROM TEMPERATURE_TABLE WHERE DEVICE_ID='".$_GET['ID']."'");

$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$result = mysqli_query($mysql, "UPDATE TEMPERATURE_TABLE SET TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'");

} else { //Если данных для этого устройства нет - добавляем
$result = mysqli_query($mysql, "INSERT TEMPERATURE_TABLE SET DEVICE_ID='".$_GET['ID']."', TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today'");

}
}

//Достаём из БД текущую команду управления реле
$result = mysqli_query($mysql, "SELECT COMMAND FROM COMMAND_TABLE WHERE DEVICE_ID = '".$_GET['ID']."'");

if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства
$Arr = mysqli_fetch_array($result);
$Command = $Arr['COMMAND'];
}

//Отвечаем на запрос текущей командой
if($Command != -1) //Есть данные для этого устройства
{
echo "COMMAND $Command EOC";
}
else
{
echo "COMMAND ? EOC";
}
}
}

?>
