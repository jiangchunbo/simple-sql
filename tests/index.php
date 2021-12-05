<?php
namespace Tqxxkj\SimpleSql\Tests;

use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Session\Defaults\DefaultSqlSessionFactory;

require_once __DIR__ . '/../vendor/autoload.php';

Environment::setProperties('localhost:test', 'mysql', 'localhost', '3306', 'root', '', 'test');

$sqlSessionFactory = new DefaultSqlSessionFactory();

$session = $sqlSessionFactory->openSession();

$usersDao = new Users($session);

$user = $usersDao->selectById0(1);

var_dump($user);
