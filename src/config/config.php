<?php

function connectDb(){
    //ホスト名、データベース名、文字コードの３つを定義する
    $host = 'mysql';
    $db = 'develop';
    $charset = 'utf8';
    $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

    //ユーザー名、パスワード
    $user = 'user';
    $pass = 'password';

    //オプション
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try{

        //上のデータを引数に入れて、PDOインスタンスを作成
        $pdo = new PDO($dsn, $user, $pass, $options);

    }catch(PDOException $e){
        echo $e->getMessage();
    }

    //PDOインスタンスを返す
    return $pdo;
}

function h(?string $value = null)
{
    if (is_null($value)) {
        return null;
    }
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}