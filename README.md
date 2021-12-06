# SimpleSql
SimpleSql 是一个用 PHP 编写的，依赖 PDO 的数据库操作工具。

## 1. 入门
### 1.1. 连接抽象
#### 1.1.1. PDO
一个 PDO 对象本质上是一个数据库连接，或者象征了一个数据库会话。在项目中，PDO 等价于 Connection（连接），二者往往是一样的。

> Simple Sql 提供了一个 PdoBuilder 用于简化构造 PDO 对象

#### 1.1.2. Connection
PDO 提供的功能可能并不完善，在其基础上进行了包装，形成的新的接口 Connection。

> MysqlConnection 是对 MySQL 数据库连接的特定实现。


#### 1.1.3. DataSource

数据源，获取数据库连接的工厂。DataSource 是 SimpleSql 提供的接口，并提供了两个实现类：

- UnpooledDataSource，每次获取连接都会创建新的 PDO
- PooledDataSource，每次获取连接会从空闲的连接池中获取


> 注意，由于不涉及多线程共享数据库连接池，因此 `PooledDataSource` 的空闲连接理论上只有一个。


### 1.2. 事务抽象
#### 1.2.1. Transaction

`Tranasction` 是 SimpleSql 的事务抽象，同时提供了一个实现类 `PdoTransaction`。

因为每个事务依赖于一个数据库的连接，因此，`Transaction` 是对连接（PDO）的一次包装。

#### TransactionFactory

`TransactionFactory` 是 SimpleSql 提供的接口，是一个提供 `Transaction` 的工厂类。SimpleSql 也提供了一个基于 PDO 的实现类 `PdoTransactionFactory`。




### 1.3. 执行器抽象

#### 1.3.1. Executor

执行器是 SimpleSql 提供的执行 SQL 语句的抽象接口，是对事务接口的包装，也可以说是对数据库连接的包装。

#### 1.3.2. BaseExecutor

`BaseExecutor` 是所有执行器的父类，应当实现所有通用接口，如事务的提交与回滚等。

#### 1.3.3. SimpleExecutor

`SimpleExecutor` 是一个简单的执行器实现。


#### 1.4. SqlSession

`SqlSession` 是数据库会话的抽象接口，也是对执行器的包装。


