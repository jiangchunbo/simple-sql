<?php


namespace Tqxxkj\SimpleSql\Tests;

use Tqxxkj\SimpleSql\Dao\BaseDao;

class Users extends BaseDao
{
    protected function tableName(): string
    {
        return 'users';
    }
}
