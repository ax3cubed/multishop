<?php
return array(
  
            'connectionString' =>"mysql:host=".readConfig("database","dbhost").";port=3306;dbname=multishop",
            'username' => "root",
            // 'password' => readDBConfig("password"),
            'charset' => "utf8",
            'pdoClass' => "NestedPDO",
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
  
);
// return [
//     'connectionString' => "mysql:host=".readConfig("database","dbhost").";dbname="."multishop",
//     'emulatePrepare' => true,
//     'username' => "root",
//     // 'password' => readDBConfig("password"),
//     'charset' => "utf8",
//     'pdoClass' => "NestedPDO",
//     'enableProfiling'=>true,
//     'enableParamLogging'=>true,
// ];