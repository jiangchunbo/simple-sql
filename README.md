# SimpleSql

SimpleSql 是一个用 PHP 编写的，依赖 PDO 的简化数据库操作工具。

参考：Java JDBC 规范, MyBatis, MyBatis Plus

## 1. 入门

### 1.1. Connection 连接抽象

#### 1.1.1. PDO

一个 PDO 对象本质上是一个数据库连接。在 SimpleSql 中。

> SimpleSql 提供了一个 `PdoBuilder` 构造器类用于简化构造 PDO 对象

#### 1.1.2. Connection

尽管 PDO 代表着一个连接（Connection），但暴露的接口可能并不完善，所以另外提供了一个 Connection 接口，具有更多抽象功能。

> `PdoConnection` 是对 PDO 的包装
> `MysqlConnection` 继承于 `PdoConnection`， 是对 MySQL 数据库连接的特定实现。

#### 1.1.3. DataSource

数据源，获取数据库连接的工厂。DataSource 是 SimpleSql 提供的接口，并提供了两个实现类：

- `UnpooledDataSource`，每次获取连接都会创建新的 PDO
- `PooledDataSource`，每次获取连接会从空闲的连接池中获取

> 注意，由于不涉及多线程共享数据库连接池，因此 `PooledDataSource` 的空闲连接理论上只有一个。

### 1.2. Statement 抽象

#### 1.2.1. PDOStatement

`PDOStatement` 是 PDO 自带的 Statement 对象

#### 1.2.2. Statement

`Statement` 是 SimpleSql 的接口，对 PDOStatement 的包装。

#### 1.2.3. PreparedStatement

`PreparedStatement` 是一个预编译的 Statement

> SimpleSql 提供了 MySQL 的 `PreparedStatement` 的实现 —— `MysqlPreparedStatement`

### 1.3. 事务抽象

事务仅对于支持事务的数据库有效，否则并不生效。

#### 1.3.1. Transaction

`Tranasction` 是 SimpleSql 的事务抽象，同时提供了一个实现类 `PdoTransaction`。

因为每个事务依赖于一个数据库的连接，因此，`Transaction` 是对连接（PDO）的包装。

#### 1.3.2. TransactionFactory

`TransactionFactory` 是 SimpleSql 提供的接口，是一个提供 `Transaction` 的工厂类。SimpleSql 也提供了一个基于 PDO 的实现类 `PdoTransactionFactory`。

### 1.4. 执行器抽象

#### 1.4.1. Executor

执行器是 SimpleSql 提供的执行 SQL 语句的抽象接口，是对事务接口的包装，也可以说是对数据库连接的包装。

#### 1.4.2. BaseExecutor

`BaseExecutor` 是所有执行器的父类，应当实现所有通用接口，如事务的提交与回滚等。

#### 1.4.3. SimpleExecutor

`SimpleExecutor` 是一个简单的执行器实现。

### 1.5. SqlSession 抽象

#### 1.5.1. SqlSession

`SqlSession` 是数据库会话的抽象接口，也是对执行器的包装。

> SimpleSql 提供了一个默认的实现类 DefaultSqlSession

#### 1.5.2. SqlSessionFactory

获得 SqlSession 的工厂类

## 2. 使用方式

### 2.1. 获得一个会话

```php
use Tqxxkj\SimpleSql\Mapping\Environment;
use Tqxxkj\SimpleSql\Session\Defaults\DefaultSqlSessionFactory;

// 创建一个环境对象并设置属性
$environment = new Environment();
$environment->setProperties('localhost:test', 'mysql', 'localhost', '3306', 'root', 'JINGjiuBUchi', 'test');

// 将环境对象赋值给 sqlSessionFactory
$sqlSessionFactory = new DefaultSqlSessionFactory($environment);

// 获得一个会话对象
$session = $sqlSessionFactory->openSession();
```

无参 openSession() 默认具有以下行为：

- 使用默认的执行器
- 使用默认的事务隔离级别（对非自动提交有效）
- 自动提交

### 2.2. 查询数据

```php
$session = $sqlSessionFactory->openSession();
$list = $session->selectList("select * from `users`");
$user = $session->selectOne("select * from `users` where `id`=?", [
    1 => [1, PDO::PARAM_INT]
]);
$user = $session->selectOne("select * from `users` where `id`=:id", [
    ':id' => [1, PDO::PARAM_INT]
]);
```

一般有三种调用方式：

- 无参执行 SQL
- 有参执行 SQL，以 ? 为占位符，传入从 1 开始的索引数字
- 有参执行 SQL，以 :name 为占位符，传入对应的映射值

> 这种 session 调用方式是最原始的方式
> 需要指定参数类型，目前支持 PDO::PARAM_INT 和 PDO::PARAM_STR 两种

### 2.3. 添加数据

```php
$session = $sqlSessionFactory->openSession();
$list = $session->insert("insert into `users`(`username`) values(?)", [
    1 => ['hello', PDO::PARAM_STR]
]);
```

### 2.4. 删除数据或者修改数据

```php
$session = $sqlSessionFactory->openSession();
$affected_num = $session->update("delete from `users` where `id`=?", [
    1 => [1, PDO::PARAM_INT]
]);
$affected_num = $session->update("update `users` set `username`=? where `id`=?", [
    1 => ['hi', PDO::PARAM_STR],
    2 => [1, PDO::PARAM_INT]
]);
```

## 3. 进阶用法

### 3.1. 使用 DAO 模型操作数据库

> 该项目约定数据库的设计中，主键不支持复合主键，且名称叫 id，类型为 int（硬编码）

#### 步骤一

定义一个对应数据库表的 Service 对象，并继承 BaseService。

声明的自定义 Service 需要实现 tableName() 显式地表示表名称

```php
class UserService extends BaseService
{
    protected function tableName(): string
    {
        return 'users';
    }
}
```

#### 步骤二

传入 $session 给 Service 进行构造，使用 BaseService 暴露的方法进行操作

```php
$session = $sqlSessionFactory->openSession(null, 0, true);
$usersService = new UserService($session);
$usersService->select();
```

### 3.2. save

调用 save 方法，传入一个关联数组，方法执行完毕之后，关联数组会得到一个以 'id' 为 key 的值，该值为实体的自增 id。

```php
$user = [
    'username' => 'jcb',
    'password' => '123456',
    'password_salt' => '789456'
];
$usersService->save($user);
```

### 3.3. saveBatch

保存多个实体，自增主键与 save 一样，传递给原数组

```php
$entityList = [
    [
        'username' => 'jcb',
        'password' => '123456',
        'password_salt' => '789456',
    ],
    [
        'username' => 'zx',
        'password' => '999999',
        'password_salt' => '88888',
    ]
];
$userService->saveBatch($entityList);
```

### 3.4. getById

通过 id 获得实体

```php
$user = $userService->getById(1);
```

### 3.5. listByIds

通过 id 数组获得实体

```php
$users = $userService->listByIds([1, 2]);
```

### 3.6. getOne

```php
$qw = QueryWrapper::get()->eq('username', 'jcb');
$user = $userService->getOne($qw);
```

> - 查询数据通过 Wrapper 进行构造
> - 内部会在末尾添加 limit 1

### 3.7. list

根据查询条件获得所有数据

```php
$qw = QueryWrapper::get();
$qw->select('count(*)', 'username');    // 支持聚合查询
$qw->groupBy('username');               // 分组
$qw->orderBy('count(*)', false);        // 聚合函数排序
$qw->having('count(*) > {0}', [5]);     // 分组筛选
$username_count = $userService->list($qw);
```

### 3.8. count

计数

```php
$qw = QueryWrapper::get()->eq('username', 'jcb');
$count = $userService->count($qw);
```