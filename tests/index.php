<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/SimpleSql/Dao/BaseService.php';

use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Session\Defaults\DefaultSqlSessionFactory;
use Tqxxkj\SimpleSql\Tests\UserService;


$environment = new Environment();
$environment->setProperties('localhost:test', 'mysql', 'localhost', '3306', 'root', 'JINGjiuBUchi', 'test');
$sqlSessionFactory = new DefaultSqlSessionFactory($environment);
/**
 * 得到一个 Session
 */
$session = $sqlSessionFactory->openSession(null, 0, false);
$usersService = new UserService($session);
$usersService->insertMore();