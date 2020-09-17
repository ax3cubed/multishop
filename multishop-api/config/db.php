<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=".readConfig("database","dbhost").";port=3306;dbname=multishop",
    'username' => "root",
    // 'password' => readDBConfig('password'),
    'charset' => 'utf8',
];