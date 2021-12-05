# SimpleSql
SimpleSql 是一个用 PHP 编写的，依赖 PDO 的数据库操作工具。


## 入门
### 获得一个数据库连接
#### PDO

一个 PDO 对象本质上是一个数据库连接，或者象征了一个数据库会话。在项目中，PDO 等价于 Connection（连接），二者往往是一样的。

> Simple Sql 提供了一个 PdoBuilder 用于简化构造 PDO 对象

#### DataSource

数据源，获取数据库连接的工厂。DataSource 是 Simple Sql 提供的接口，并提供了两个实现类：

- UnpooledDataSource，每次获取连接都会创建新的 PDO
- PooledDataSource，每次获取连接会从空闲的连接池中获取


> 注意，由于不涉及多线程共享数据库连接池，因此 `PooledDataSource` 的空闲连接理论上只有一个。


### 获得一个事务
#### TransactionFactory

`TransactionFactory` 是 SimpleSql 提供的接口，用于获取数据库事务。SimpleSql 也提供了一个基于 PDO 的实现类 `PdoTransactionFactory`。

#### Transaction

`Tranasction` 是 SimpleSql 的事务抽象，同时提供了一个实现类 `PdoTransaction`。

因为每个事务依赖于一个数据库的连接，因此，`Transaction` 是对连接（PDO）的一次包装。


### Executor


