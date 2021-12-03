<?php

use Tqxxkj\SimpleSql\DataSource\Environment;
use Tqxxkj\SimpleSql\DataSource\SqlSessionFactory;

//require_once __DIR__ . '/../src/SimpleSql/DataSource/Environment.php';
//require_once __DIR__ . '/../src/SimpleSql/DataSource/SqlSessionFactory.php';
//require_once __DIR__ . '/../src/SimpleSql/DataSource/SimpleDataSource.php';
//require_once __DIR__ . '/../src/SimpleSql/DataSource/SimpleDataSource.php';

require_once __DIR__ . '/../vendor/autoload.php';

Environment::setProperties('localhost:test', 'mysql', 'localhost', '3306', 'root', 'JINGjiuBUchi', 'test');

$sqlSessionFactory = new SqlSessionFactory();

$session = $sqlSessionFactory->openSession();

$list = $session->selectList("select * from `users`", []);

var_dump($list);
