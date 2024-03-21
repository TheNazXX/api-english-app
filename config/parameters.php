<?php 

return [
  'debug' => true,
  'users' => [
    'admin' => '123'
  ],
  'db_config' => [
    'host' => 'localhost',
    'dbname' => 'words-api',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'options' => [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ] 
  ]
];