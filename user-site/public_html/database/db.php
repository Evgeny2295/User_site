<?php
session_start();
include_once 'connect.php';
//Функция тестирования

function test($value){

    echo '<pre>';
    print_r($value);
    echo '</pre>';
    exit();

}

//
//Проверка выполнения запроса
function dbCheckError($query){
    $errInfo = $query->errorInfo();

    if($errInfo[0] !== PDO::ERR_NONE){
        echo $errInfo[2];
        exit();
    }
    return true;
}


//Запись в таблицу БД
function add_user($table,$params){
    global $pdo;
    $i = 0;
    $coll = '';
    $mask = '';
    foreach ($params as $key => $value){
        if($i === 0){
            $coll = $coll . "$key";
            $mask = $mask . "'" . "$value" . "'";

        }else{
            $coll = $coll . ", $key";
            $mask = $mask . ", '" . "$value" . "'" ;

        }
        $i++;
    }

    $sql = "INSERT INTO $table ($coll) VALUES ($mask)";

    $query = $pdo->prepare($sql);
    $query->execute();
    dbCheckError($query);

    return;
}

//Запрос на получение одной строки из таблицы

function get_user_by_email($table,$params = []){
    global $pdo;
    $sql = "SELECT * FROM $table";
    if(!empty($params)){
        $i = 0;
        foreach($params as $key=>$value){
            if(!is_numeric($value)){
                $value = "'".$value."'";
            }
            if($i === 0){
                $sql = $sql . " WHERE $key=$value";
            }else{
                $sql = $sql . " AND $key=$value";
            }
            $i++;
        }
    }

    $query = $pdo->prepare($sql);
    $query->execute();
    dbCheckError($query);

    return $query->fetch();//fetch(PDO::fetch_assoc);-ассоц массив
}
